<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
    /*faizan css*/
        .main table tr{
    /*        display:block;*/
    /*        margin:auto;*/
        }
            body{
                font-family:sans-serif;
            }
            page {
              background: white;
              display: block;
              margin: 0 auto;
              margin-bottom: 0.5cm;
            }
            page[size="A4"] {  
              width: 21cm;
              height: 29.7cm; 
            }
            .main{
    /*            padding: 3.5rem 3rem;*/
                padding-left: 3.5rem 0;
            }   
            table{
    /*          border: 1px solid gray;*/
                width: 100%;
                border-collapse: collapse;
            }
            thead{
                display: block;
                border-collapse: separate;
            }
            .head{
                text-align: left;
                padding:8px 0 20px 100px;
            }
            p{font-size: .8rem;}
            .head h3{margin: 0;font-weight: 550; font-size:16px}
            .head p{margin-bottom: 0;margin-top: 5px;font-weight: lighter; line-height:1.4; font-size:15px}
            .head2 h2{
                margin: 0;
                padding-top: 60px;
                text-align: right;
            }
            tbody{
                display: block;
            }
            tbody p{
                margin: 0;
            }
            .address p{
                line-height: 1.5;
                padding-left: 5px;
            }
            .name1{
                width: 54.55%;
                padding-left: 5px;
                border-right: 1px solid gray;
                padding-top: 10px;
                padding-bottom: 10px;
            }
            .name2{
                padding-left: 5px;
                padding-bottom: 15px;
            }
            .width1{
                width: 30px;
                border-right: 1px solid gray;
            }
            .width2{
                width: 180px;
                border-right: 1px solid gray;
            }
            .width3{
                width: 70px;
                border-right: 1px solid gray;
            }
            .width4{
                width: 60px;
                border-right: 1px solid gray;
            }
            .width5{
                    width: 70px;
                    border-right: 1px solid gray;
            }
            .width6{
                width: 130px;
                border-right: 1px solid gray;
                border-bottom: 1px solid gray;
                text-align: center;
            }
            .width7{
                width: 90px;
                border-right: none!important;
            }
            .detailed{border-top: none;border-bottom: none;}
            .detailed td{border-right: 1px solid gray;font-size: .8rem;padding-left: 5px;padding-right: 5px;}
            .border-top td{
    /*          border-top: 1px solid gray;*/
            }



        /*faizan*/
        .invoice th{
            text-align:left;
            border-right:1px solid #000;
            padding-right:10px;
            padding-left:8px;
        }
        .row-flex {
            display:grid;
            grid-template-columns: 10% 44% 10% 9% 6% 4% 8% 9%;
            text-align:center;
        }
        .row-flex td{
            display:flex;
            justify-content:center;
            align-items:center;
        }
        .row-flex td:nth-child(8){
            border-right:0;
        }
        .border-top.row-flex td:nth-child(1){
            color: transparent;
        }
        .bank-row{
            display:grid;
            grid-template-columns:1fr 1fr;
        }
        .bank-row td{
            text-align:left;
        }
        .inp{
            display:block;
            width:100%;
        }
        .header-logo{
            margin: 0 auto 40px;
    /*      text-align: center;*/
            display: block;
        }
        .header-logo img{
            width:150px!important;
        }
        .baar-img{
    /*        width: 180px;*/
            margin: 30px auto 0;
            text-align: center;
            display: block;
        }
        .baar-img img{
            width:180px;
        }
        .border-bottom{
            display:block;
            height:4px;
            background: #000;
            width:100%;
    /*        margin-top:50px;*/
            margin-top:30px;
        }
        </style>
</head>
<body>
    <page size="A4">
        <div class="main">
            <table>
                <thead>
                    
                    <tr>
                        
                        <th class="head" style="width: 77.5%; padding-left:190px;">
                            <a href="http://eprintcafe.com" class="header-logo" style="text-align:center;">
                                <img src="https://www.eprintcafe.com/public/frontend/images/logo.png" style="width:150px;">
                            </a>
                            <h3 style="font-style: italic; margin-bottom:5px;">Buyer’s Address</h3>
                            <table>
                                <tr class="inp">
                                    <td style="display:flex; align-items:end;">
                                        <h3 style="margin-right:5px;">To, </h3>
                                        <span style="width:100%; height:auto;background:transparent; border-bottom:2px solid #000; font-size:14px; margin-left:4px;">{{ $customerAddress->shipping_name }}</span>
                                    </td>
                                </tr>
                                <tr class="inp">
                                    <td style="display:flex; align-items:end; padding-top:5px">
                                        <h3 style="margin-right:5px;">Amount </h3>
                                        <span style="width:100%; height:auto;background:transparent; border-bottom:2px solid #000; font-size:14px; margin-left:4px;">{{ $priceData->total }}</span>
                                    </td>
                                </tr>
                                <tr class="inp">
                                    <td style="display:flex; padding-top:5px">
                                        <h3 style="margin-right:5px; width:98px;">Address :</h3>
                                        <span style="width:100%; height:auto;background:transparent; border-bottom:2px solid #000; font-size:14px; margin-left:4px;">{{ $customerAddress->shipping_address }}</span>

                                    </td>
                                </tr>
                                 <tr class="inp">
                                    <td style="display:flex; align-items:end; height:24px;">
                                        <span style="width:100%; height:auto;background:transparent; border-bottom:2px solid #000; font-size:14px; margin-left:4px;">{{ $customerAddress->shipping_city }}</span>
                                    </td>
                                </tr>
                                 <tr class="inp">
                                    <td style="display:flex; align-items:end; height:24px;">
                                        <span style="width:100%; height:auto;background:transparent; border-bottom:2px solid #000; font-size:14px; margin-left:4px;">{{ $customerAddress->shipping_state }}</span>
                                    </td>
                                </tr>
                            </table>
                            <h3 style="font-style: italic; margin-top:12px;">Pin Code. : {{ $customerAddress->shipping_pincode }}</h3>
                            <h3 style="font-style: italic; margin-top:12px;">Contact No. {{ $customerAddress->shipping_phone }}</h3>
                            <!-- <h3 style="font-style: italic; margin-top:12px;">Courier Bar Code (Docket No.) 454541</h3> -->
                            <a href="http://eprintcafe.com" class="baar-img">
                                <!-- <img src="baar-img.png" style="width:220px;"> -->
                                {!! DNS1D::getBarcodeSVG($order->shipping_label_number, 'C128A', 2.5, 80) !!}
                            </a>
                            <span class="border-bottom"></span>

                        </th>
                        <th class="head2" style="width: 25%;">
                            <h2 style="opacity: 0;">TAX INVOICE</h2>
                        </th>
                    </tr>
                </thead>                
            </table>
            <table>
                <thead>
                    <tr>
                        <th style="text-align:center; padding-left:100px; font-style:italic;">
                            <h3>if undelivered please return to :</h3>
                        </th>
                    </tr>
                    <tr>
                            
                        <th class="head" style="width: 100%; padding-left:250px;">
                            <a href="http://eprintcafe.com" class="header-logo" style="margin-bottom:10px;">
                                <img src="https://www.eprintcafe.com/public/frontend/images/logo.png" style="width:110px;">
                            </a>
                            <h3 style="font-style: italic; font-size:14px;">A Unit of INDIA INTTECH PVT. LTD.</h3>
                            <p style="padding-right:56px;">
                                ABOVE SHYAM ELECTROSTAT <br>
                                11 VEER SAVARKAR BLOCK, VIKAS MARG, SHAKARPUR,
                                NEAR NIRMAN VIHAR METRO STATION DELHI-110092 
                                INDIA
                            </p>
                            <h3 style="font-style: italic; margin-top:12px;">Customer care : +91 8448193390</h3>

                        </th>
                        <th class="head2" style="width: 25%;">
                            <h2 style="opacity: 0;">TAX INVOICE</h2>
                        </th>
                    </tr>
                </thead>                
            </table>
        </div>
    </page>
</body>
</html>