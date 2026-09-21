<html>
<head>
<style>
/* General table styling */
table {
  width: 100%;
  border-collapse: collapse;
  margin: 20px 0;
  font-size: 16px;
  text-align: left;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Table header styling */
thead {
  background-color: #497ffc; /*#4CAF50;*/
  color: white;
}

thead th {
  padding: 12px 15px;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-weight: bold;
}

/* Table body styling */
tbody tr {
  border-bottom: 1px solid #ddd;
  transition: background-color 0.2s ease-in-out;
}

tbody tr:nth-child(even) {
  background-color: #f9f9f9; /* Zebra striping */
}

tbody tr:hover {
  background-color: #f1f1f1;
}

tbody td {
  padding: 10px 15px;
}

/* Responsive behavior for small screens */
@media screen and (max-width: 600px) {
  table {
    border: 0;
  }

  thead {
    display: none; /* Hide table header on small screens */
  }

  tbody tr {
    display: block;
    margin-bottom: 10px;
  }

  tbody td {
    display: block;
    text-align: right;
    padding: 10px;
    position: relative;
  }

  tbody td::before {
    content: attr(data-label);
    position: absolute;
    left: 0;
    width: 50%;
    padding-left: 15px;
    font-weight: bold;
    text-align: left;
  }
}
</style>
    </head>
    
<body>
    <table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Position</th>
      <th>Office</th>
      <th>Age</th>
      <th>Start Date</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td data-label="Name">John Doe</td>
      <td data-label="Position">Software Engineer</td>
      <td data-label="Office">New York</td>
      <td data-label="Age">32</td>
      <td data-label="Start Date">2015-01-25</td>
    </tr>
    <tr>
      <td data-label="Name">Jane Smith</td>
      <td data-label="Position">Product Manager</td>
      <td data-label="Office">London</td>
      <td data-label="Age">29</td>
      <td data-label="Start Date">2017-08-19</td>
    </tr>
    <!-- Add more rows as needed -->
  </tbody>
</table>

    
    
    </body>
