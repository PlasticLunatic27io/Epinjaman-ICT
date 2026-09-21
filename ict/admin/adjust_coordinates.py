#!/usr/bin/env python3
"""
Helper script untuk adjust coordinates pada JTM PDF
"""

import sys
from pypdf import PdfReader, PdfWriter
from reportlab.pdfgen import canvas
from reportlab.lib.colors import red, blue, green, gray
from io import BytesIO

def create_grid_overlay(page_width, page_height):
    """Create grid overlay untuk finding coordinates"""
    packet = BytesIO()
    can = canvas.Canvas(packet, pagesize=(page_width, page_height))
    
    # Draw vertical grid lines
    can.setStrokeColor(gray)
    can.setLineWidth(0.3)
    for x in range(0, int(page_width), 10):
        can.line(x, 0, x, page_height)
    
    # Draw horizontal grid lines
    for y in range(0, int(page_height), 10):
        can.line(0, y, page_width, y)
    
    # Draw major grid lines (every 50 points)
    can.setStrokeColor(blue)
    can.setLineWidth(0.5)
    for x in range(0, int(page_width), 50):
        can.line(x, 0, x, page_height)
    
    for y in range(0, int(page_height), 50):
        can.line(0, y, page_width, y)
    
    # Label major grid lines
    can.setFont("Helvetica", 6)
    can.setFillColor(blue)
    
    # X-axis labels
    for x in range(0, int(page_width), 50):
        can.drawString(x + 2, 2, str(x))
        can.drawString(x + 2, page_height - 8, str(x))
    
    # Y-axis labels
    for y in range(0, int(page_height), 50):
        can.drawString(2, y + 2, str(y))
        can.drawString(page_width - 20, y + 2, str(y))
    
    # Draw reference markers at common form positions
    can.setFillColor(red)
    can.setFont("Helvetica", 7)
    
    # Common Y positions for JTM form (estimated)
    markers = [
        (50, 745, "Area: Nama"),
        (50, 728, "Area: No.ID"),
        (50, 711, "Area: No.Tel"),
        (50, 694, "Area: Jawatan"),
        (50, 677, "Area: Unit"),
        (50, 645, "Area: Tujuan"),
        (50, 628, "Area: Tarikh"),
        (50, 611, "Area: Masa"),
        (50, 594, "Area: Lokasi"),
        (50, 550, "Area: Table Start"),
    ]
    
    for x, y, label in markers:
        # Draw small circle
        can.circle(x, y, 3, fill=1)
        # Draw label
        can.drawString(x + 6, y - 2, label)
    
    # Draw page info
    can.setFillColor(green)
    can.setFont("Helvetica-Bold", 10)
    can.drawString(page_width/2 - 50, page_height - 20, 
                   f"Page: {page_width} x {page_height} pt")
    
    can.save()
    packet.seek(0)
    return PdfReader(packet)


def create_test_text_overlay(page_width, page_height):
    """Create test text at various positions"""
    packet = BytesIO()
    can = canvas.Canvas(packet, pagesize=(page_width, page_height))
    
    can.setFont("Helvetica", 10)
    can.setFillColorRGB(1, 0, 0)  # Red text for visibility
    
    # Test positions
    test_data = [
        (145, 745, "NAMA TEST"),
        (145, 728, "ID TEST"),
        (145, 711, "TEL TEST"),
        (145, 694, "JAWATAN TEST"),
        (145, 677, "UNIT TEST"),
        (145, 645, "TUJUAN TEST"),
        (165, 628, "10/02/2026"),
        (340, 628, "12/02/2026"),
        (165, 611, "09:00"),
        (340, 611, "17:00"),
        (145, 594, "LOKASI TEST"),
    ]
    
    for x, y, text in test_data:
        can.drawString(x, y, text)
        # Draw crosshair at position
        can.setStrokeColorRGB(1, 0, 0)
        can.line(x-5, y, x+5, y)
        can.line(x, y-5, x, y+5)
    
    # Test table
    can.setFont("Helvetica", 9)
    table_y = 550
    for i in range(3):
        y = table_y - (i * 18)
        can.drawString(35, y, str(i+1))
        can.drawString(70, y, f"ITEM TEST {i+1}")
        can.drawString(370, y, "SERIAL")
        can.drawString(480, y, "TAG")
    
    can.save()
    packet.seek(0)
    return PdfReader(packet)


def add_grid_to_pdf(template_path, output_path):
    """Add grid overlay to PDF"""
    template = PdfReader(template_path)
    output = PdfWriter()
    
    first_page = template.pages[0]
    page_width = float(first_page.mediabox.width)
    page_height = float(first_page.mediabox.height)
    
    print(f"📐 PDF Size: {page_width} x {page_height} points")
    print(f"   ({page_width/2.83:.1f} x {page_height/2.83:.1f} mm)")
    
    # Create grid overlay
    grid_overlay = create_grid_overlay(page_width, page_height)
    
    # Merge with template
    page = template.pages[0]
    page.merge_page(grid_overlay.pages[0])
    output.add_page(page)
    
    # Add other pages
    for i in range(1, len(template.pages)):
        output.add_page(template.pages[i])
    
    # Save
    with open(output_path, 'wb') as f:
        output.write(f)
    
    print(f"✓ Grid PDF created: {output_path}")
    print(f"\nHow to use:")
    print(f"1. Open {output_path}")
    print(f"2. Look at the blue grid lines and numbers")
    print(f"3. Find where you want text to appear")
    print(f"4. Note the X (horizontal) and Y (vertical) coordinates")
    print(f"5. Update coordinates in fill_jtm_overlay.py")
    print(f"\nRemember: Y=0 is at BOTTOM, Y increases UPWARD")


def add_test_text_to_pdf(template_path, output_path):
    """Add test text to PDF to check positions"""
    template = PdfReader(template_path)
    output = PdfWriter()
    
    first_page = template.pages[0]
    page_width = float(first_page.mediabox.width)
    page_height = float(first_page.mediabox.height)
    
    # Create test overlay
    test_overlay = create_test_text_overlay(page_width, page_height)
    
    # Merge
    page = template.pages[0]
    page.merge_page(test_overlay.pages[0])
    output.add_page(page)
    
    # Add other pages
    for i in range(1, len(template.pages)):
        output.add_page(template.pages[i])
    
    # Save
    with open(output_path, 'wb') as f:
        output.write(f)
    
    print(f"✓ Test PDF created: {output_path}")
    print(f"\nCheck if red test text aligns with form fields")
    print(f"If not aligned, adjust coordinates in fill_jtm_overlay.py")


if __name__ == '__main__':
    if len(sys.argv) < 3:
        print("Usage:")
        print("  Show grid:       python3 adjust_coordinates.py grid <input.pdf> <output.pdf>")
        print("  Test positions:  python3 adjust_coordinates.py test <input.pdf> <output.pdf>")
        print("\nExamples:")
        print("  python3 adjust_coordinates.py grid jtm.pdf grid_output.pdf")
        print("  python3 adjust_coordinates.py test jtm.pdf test_output.pdf")
        sys.exit(1)
    
    mode = sys.argv[1]
    
    if mode == 'grid':
        if len(sys.argv) != 4:
            print("Error: grid mode needs <input.pdf> <output.pdf>")
            sys.exit(1)
        add_grid_to_pdf(sys.argv[2], sys.argv[3])
    
    elif mode == 'test':
        if len(sys.argv) != 4:
            print("Error: test mode needs <input.pdf> <output.pdf>")
            sys.exit(1)
        add_test_text_to_pdf(sys.argv[2], sys.argv[3])
    
    else:
        print(f"Error: Unknown mode '{mode}'")
        print("Use 'grid' or 'test'")
        sys.exit(1)