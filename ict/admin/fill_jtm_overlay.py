import sys, json
from datetime import datetime
from io import BytesIO
from xml.sax.saxutils import escape
from pypdf import PdfReader, PdfWriter
from reportlab.pdfgen import canvas
from reportlab.lib.utils import ImageReader
from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle
from reportlab.lib.styles import getSampleStyleSheet

def fit(c, text, x, y, width, size=9):
    text = str(text or '').replace('\n', ' ')
    c.setFont('Helvetica', size)
    while text and c.stringWidth(text, 'Helvetica', size) > width:
        text = text[:-1]
    c.drawString(x, y, text)

def create_text_overlay(data, width, height):
    buf = BytesIO(); c = canvas.Canvas(buf, pagesize=(width, height))
    for key,x,y,w in [('nama',170,705,135),('no_id_staf',170,693,135),('no_tel',170,682,135),('jawatan',170,672,135),('unit_bahagian',170,662,135),('tujuan_tempahan',170,630,157),('tarikh_dari',193,618,67),('tarikh_hingga',283,618,43),('masa_dari',194,607,66),('masa_hingga',285,607,42),('lokasi_penggunaan',170,597,157)]:
        value=data.get(key,'')
        if key in ['tarikh_dari','tarikh_hingga'] and value:
            try: value=datetime.strptime(value[:10],'%Y-%m-%d').strftime('%d/%m/%Y')
            except ValueError: pass
        fit(c,value,x,y,w,7 if key.startswith('tarikh_') else 8)
    for i,item in enumerate(data.get('equipment',[])[:9]):
        y=547-18*i
        fit(c,item.get('name',''),65,y,257,8)
        fit(c,item.get('serial_no',''),330,y,58,7)
        fit(c,item.get('tagging_no',''),400,y,114,8)
    a=data.get('approval')
    if a:
        c.drawImage(ImageReader(a['image_path']),345,639,width=170,height=48,preserveAspectRatio=True,anchor='c',mask='auto')
        fit(c,a['lead_name'],401,629,119,7)
        fit(c,'Disahkan dalam sistem',401,618,119,7)
        fit(c,a['approved_at'],401,607,119,7)
        fit(c,a['jtm_name'],366,302,150,8)
        c.setFillColor(colors.white);c.rect(351,286,169,11,fill=1,stroke=0);c.setFillColor(colors.black)
        fit(c,a['confirmed_at'],355,288,162,7)
    c.save(); buf.seek(0); return PdfReader(buf)

def audit_appendix(data):
    a=data['approval']; buf=BytesIO(); styles=getSampleStyleSheet()
    styles['Title'].textColor=colors.HexColor('#1e3a8a')
    styles['Normal'].fontSize=9; styles['Normal'].leading=13
    elements=[Paragraph('Approval Record',styles['Title']),Paragraph('ePinjamICT - Borrowing request #'+str(data['borrow_id']),styles['Heading2']),Spacer(1,12)]
    values=[('Approval reference',str(a['id'])+' / round '+str(a['round'])),('Applicant',data['nama']+' / '+data['no_id_staf']),('Department',data['unit_bahagian']),('JTM confirmation',a['jtm_name']+' / '+a['confirmed_at']),('Department approval',a['lead_name']+' / '+a['approved_at']),('Borrowing period',data['start_date']+' to '+data['end_date']),('Purpose / location',data['tujuan_tempahan']+' / '+data['lokasi_penggunaan'])]
    table=Table([[Paragraph(escape(k),styles['Normal']),Paragraph(escape(v),styles['Normal'])] for k,v in values],colWidths=[125,380])
    table.setStyle(TableStyle([('VALIGN',(0,0),(-1,-1),'TOP'),('BACKGROUND',(0,0),(0,-1),colors.HexColor('#eef2fa')),('GRID',(0,0),(-1,-1),.4,colors.HexColor('#dce3ef')),('LEFTPADDING',(0,0),(-1,-1),10),('TOPPADDING',(0,0),(-1,-1),8),('BOTTOMPADDING',(0,0),(-1,-1),8)]))
    elements.extend([table,Spacer(1,18),Paragraph('JTM confirmed &gt; Department approved &gt; Handover recorded separately',styles['Normal']),Spacer(1,18),Paragraph('Confirmed Equipment',styles['Heading2'])])
    rows=[['Item / accessories','Asset tag','Serial','Qty']]
    for item in data['equipment']:
        text=item['name']+(' - '+item.get('description','') if item.get('description') else '')
        rows.append([Paragraph(escape(text),styles['Normal']),Paragraph(escape(item.get('tagging_no','')),styles['Normal']),Paragraph(escape(item.get('serial_no','')),styles['Normal']),str(item.get('quantity',1))])
    table=Table(rows,colWidths=[235,110,110,50],repeatRows=1)
    table.setStyle(TableStyle([('BACKGROUND',(0,0),(-1,0),colors.HexColor('#1e3a8a')),('TEXTCOLOR',(0,0),(-1,0),colors.white),('GRID',(0,0),(-1,-1),.4,colors.HexColor('#dce3ef')),('VALIGN',(0,0),(-1,-1),'TOP'),('TOPPADDING',(0,0),(-1,-1),9),('BOTTOMPADDING',(0,0),(-1,-1),9)]))
    elements.extend([table,Spacer(1,18),Paragraph('This document embeds the image explicitly authorized at approval time. Replacing the profile image does not modify this PDF. Borrower receipt and return signatures remain separate.',styles['Normal']),Spacer(1,12),Paragraph('Request fingerprint: '+a['snapshot_hash'],styles['Normal'])])
    SimpleDocTemplate(buf,pagesize=A4,rightMargin=45,leftMargin=45,topMargin=45,bottomMargin=45).build(elements)
    buf.seek(0); return PdfReader(buf)

def fill_pdf_template(template_path,data_json_path,output_path):
    with open(data_json_path,encoding='utf-8') as stream: data=json.load(stream)
    source=PdfReader(template_path); writer=PdfWriter(); page=source.pages[0]
    page.merge_page(create_text_overlay(data,float(page.mediabox.width),float(page.mediabox.height)).pages[0]); writer.add_page(page)
    for page in source.pages[1:]: writer.add_page(page)
    if data.get('approval'):
        for page in audit_appendix(data).pages: writer.add_page(page)
    with open(output_path,'wb') as stream: writer.write(stream)
    return True

if __name__=='__main__':
    try:
        if len(sys.argv)!=4: raise ValueError('Expected template, data and output paths.')
        fill_pdf_template(*sys.argv[1:]); print('PDF generated successfully.')
    except Exception as error:
        print('PDF generation failed: '+str(error)); sys.exit(1)
