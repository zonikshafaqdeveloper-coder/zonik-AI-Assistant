<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Note</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            background: white;
        }
        
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .document {
            border: 1px solid #000;
        }
        
        .header {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            padding: 8px;
            border-bottom: 1px solid #000;
            background: white;
        }
        
        .company-section {
            padding: 10px;
            border-bottom: 1px solid #000;
        }
        
        .company-name {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 3px;
        }
        
        .company-info {
            font-size: 10px;
            line-height: 1.5;
        }
        
        .two-column {
            display: table;
            width: 100%;
            border-bottom: 1px solid #000;
        }
        
        .column {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
            border-right: 1px solid #000;
        }
        
        .column:last-child {
            border-right: none;
        }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .party-name {
            font-weight: bold;
            font-size: 11px;
        }
        
        .party-info {
            font-size: 10px;
            line-height: 1.5;
        }
        
        .invoice-info {
            display: table;
            width: 100%;
            border-bottom: 1px solid #000;
            font-size: 10px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-cell {
            display: table-cell;
            padding: 3px 10px;
            border-bottom: 1px solid #000;
        }
        
        .info-cell:first-child {
            border-right: 1px solid #000;
        }
        
        .info-label {
            display: inline-block;
            width: 140px;
        }
        
        .supply-place {
            padding: 5px 10px;
            border-bottom: 1px solid #000;
            font-weight: bold;
            font-size: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        
        th {
            border: 1px solid #000;
            padding: 5px;
            font-weight: bold;
            text-align: center;
            background: white;
            font-size: 10px;
        }
        
        td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .amount-section {
            border-bottom: 1px solid #000;
            padding: 10px;
        }
        
        .amount-label {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 3px;
        }
        
        .amount-text {
            font-weight: bold;
            font-size: 11px;
        }
        
        .footer-section {
            display: table;
            width: 100%;
        }
        
        .footer-left {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
            border-right: 1px solid #000;
            font-size: 10px;
        }
        
        .footer-right {
            display: table-cell;
            width: 50%;
            padding: 10px;
            text-align: right;
            vertical-align: top;
            font-size: 10px;
        }
        
        .signature-space {
            margin-top: 50px;
            font-weight: bold;
        }
        
        .footer-note {
            text-align: center;
            padding: 5px;
            font-size: 9px;
            border-top: 1px solid #000;
        }
        
        @media print {
            body {
                margin: 0;
            }
            .page {
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="document">
            <!-- Header -->
            <div class="header">Credit Note</div>
            
            <!-- Company Details -->
            <div class="company-section">
                <div class="company-name">Infigourmet Networks Private Limited</div>
                <div class="company-info">
                    Unit no 42  (Near Panchal Furniture),<br>
                    Nav Nandanvan Industrial estate,<br>
                    Asha Nagar, Mulund West,<br>
                    Mumbai 400080.( Landmark : Gold Gym Mulund West.)<br>
                    Fssai No - 11525009000305<br>
                    GSTIN/UIN: 27AAICI2086H1ZE<br>
                    State Name : Maharashtra, Code : 27<br>
                    Contact : +91-9869612312
                </div>
            </div>
            
            <!-- Consignee and Buyer -->
            <div class="two-column">
                <div class="column">
                    <div class="section-title">Consignee (Ship to)</div>
                    <div class="party-name">Aarya Foodz</div>
                    <div class="party-info">
                        G No.962/1/1, Pangare Mala Govind Nagar Near,<br>
                        Bhujbal farm, CIDCO Nasik, Mumbai<br>
                        GSTIN/UIN : 27AOMPD2737B1Z9<br>
                        State Name : Maharashtra, Code : 27
                    </div>
                </div>
                <div class="column">
                    <div class="section-title">Buyer (Bill to)</div>
                    <div class="party-name">Aarya Foodz</div>
                    <div class="party-info">
                        G No.962/1/1, Pangare Mala Govind Nagar Near,<br>
                        Bhujbal farm, CIDCO Nasik, Mumbai<br>
                        GSTIN/UIN : 27AOMPD2737B1Z9<br>
                        State Name : Maharashtra, Code : 27
                    </div>
                </div>
            </div>
            
            <!-- Invoice Details -->
            <div class="invoice-info">
                <div class="info-row">
                    <div class="info-cell">
                        <span class="info-label">Credit Note No.</span> 1
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Dated</span> 31-Jan-26
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-cell">
                        <span class="info-label">Buyer's Order No.</span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Mode/Terms of Payment</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-cell">
                        <span class="info-label">Dispatch Doc No.</span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Dated</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-cell">
                        <span class="info-label">Dispatched through</span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Terms of Delivery</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-cell" style="border-bottom: none;">
                        <span class="info-label">Destination</span>
                    </div>
                    <div class="info-cell" style="border-bottom: none;">
                    </div>
                </div>
            </div>
            
            <!-- Place of Supply -->
            <div class="supply-place">Place of Supply : Maharashtra</div>
            
            <!-- Items Table -->
            <table>
                <thead>
                    <tr>
                        <th style="width: 30px;">Sl<br>No.</th>
                        <th style="width: 200px;">Description of Goods</th>
                        <th style="width: 70px;">HSN/SAC</th>
                        <th style="width: 50px;">GST<br>Rate</th>
                        <th style="width: 70px;">Quantity</th>
                        <th style="width: 80px;">Rate per</th>
                        <th style="width: 80px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Amul Butter IP , 500 gm</td>
                        <td class="text-center">04051000</td>
                        <td class="text-center">5 %</td>
                        <td class="text-center">1 Pkt</td>
                        <td class="text-right">243.73 Pkt</td>
                        <td class="text-right">243.73</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right">CGST 2.5% &nbsp;&nbsp;&nbsp; 2.50 %</td>
                        <td class="text-right">6.09</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right">SGST 2.5% &nbsp;&nbsp;&nbsp; 2.50 %</td>
                        <td class="text-right">6.09</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right">Round Off</td>
                        <td class="text-right">0.09</td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td colspan="4"></td>
                        <td class="text-center">Total</td>
                        <td class="text-center">1 Pkt</td>
                        <td class="text-right">₹ 256.00</td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Amount in Words -->
            <div class="amount-section">
                <div class="amount-label">Amount Chargeable (in words)</div>
                <div class="amount-text">INR Two Hundred Fifty Six Only</div>
            </div>
            
            <!-- Footer -->
            <div class="footer-section">
                <div class="footer-left">
                    E. & O.E
                </div>
                <div class="footer-right">
                    for Infigourmet Networks Private Limited
                    <div class="signature-space">Authorised Signatory</div>
                </div>
            </div>
            
            <!-- Computer Generated Note -->
            <div class="footer-note">This is a Computer Generated Document</div>
        </div>
    </div>
</body>
</html>