<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        /*page {
          background: white;
          display: block;
          margin: 0 auto;
          margin-bottom: 0.5cm;
        }*/
        /*page[size="A4"] {  
          width: 21cm;
          height: 29.7cm; 
        }*/
        .main{padding: 3.5rem 3rem;}
        table{
            border: 1px solid gray;
            width: 100%;
            border-collapse: collapse;
        }
        thead{
            display: block;
/*          border-bottom: 1px solid gray;*/
            border-collapse: separate;
        }
        .head{
            text-align: left;
/*          padding-left: 20px;*/
            text-align:center;
/*          padding:8px 0 20px 45px;*/
            padding: 0px 0 10px 14px;
        }
        p{font-size: .8rem;}
        .head h3{margin: 0;font-weight: 600;}
        .head p{margin-bottom: 0;margin-top: 5px;font-weight: lighter; line-height:1.4}
        .head2 h2{
            margin: 0;
            padding-top: 60px;
            text-align: right;
        }
        tbody{
            display: block;
        }
        tbody p{margin: 0;}
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
        .detailed td{border-right: 1px solid gray;font-size: .8rem;padding-left:3px;padding-right:3px;}
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
        display:flex;
/*      grid-template-columns: 10% 44% 10% 9% 6% 4% 8% 9%;*/
/*        grid-template-columns: 8% 41% 11% 10% 6% 5% 10% 9%;*/
        text-align:center;
    }
    .row-flex td{
/*        display:flex;*/
/*        justify-content:center;*/
/*        align-items:center;*/
        padding:0 9.1px;
    }
    .row-flex td:nth-child(8){
        border-right:0;
    }
    .border-top.row-flex td{
        color: transparent;
    }
    .bank-row{
/*        display:grid;*/
/*        grid-template-columns:1fr 1fr;*/
    }
    .bank-row td{
        text-align:left;
    }
    .color_ td{
        color:#000!important;
    }
    .msg-4126074068589179033 .m_-4126074068589179033row-flex td{
        justify-content:center;
        align-items:center;
    }
    </style>
</head>
<body>
    <div class="main" style="width:600px; margin: auto;">
            <table>
                <thead style="border-bottom:1px solid gray;">
                    <tr class="invoice">
                        <th>Invoice No.</th>
                        <th>{{ $order->invoice_number }}</th>
                        <th>Dated</th>
                        <th style="border-right:0;">{{ date('d-m-Y', strtotime($order->created_at)); }}</th>
                    </tr>
                </thead>
                <thead>
                    <tr class="invoice">
                        <th style="border-right:0;">Ref. No.</th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th style="width: 20%; padding-top:10%; display:flex;">
                            <!-- <a href="http://eprintcafe.com">
                                <img src="https://www.eprintcafe.com/public/frontend/images/logo.png" style="width:110px;">
                            </a> -->
                        </th>
                        <th class="head" style="width: 60.3%;">
                            <!-- <img src="https://www.eprintcafe.com/public/frontend/images/logo.png" style="width:110px;"> -->
                            <a href="http://eprintcafe.com">
                                <img src="https://www.eprintcafe.com/public/frontend/images/logo.png" style="width:110px;">
                            </a>
                            <h3>A unit of India Int-tech pvt ltd</h3>
                            <p>
                                1st Floor, 11 Veer Savarkar Block, Vikas Marg, 
                                <br>
                                Near Nirman Vihar Metro station, Shakarpur, Delhi -110092
                                <br>
                                <strong>GSTIN/UIN: 07AAECI0809E1ZZ</strong>
                                <br>
                                State Name :  Delhi, Code : 07
                                <br>
                                CIN: U52100DL2016PTC292154
                                <br>
                                E-Mail : mail@eprintcafe.com
                                <br>
                                www.eprintcafe.com
                            </p>
                        </th>
                        <th class="head2" style="width: 25%;">
                            <h2 style="opacity: 0;">TAX INVOICE</h2>
                        </th>
                    </tr>
                </thead>
                <thead>
                    <tr style="text-align: left;">
                        <td style="width:21%; padding-left:10px;"><strong>Party Name:</strong> {{ $customerName }}</td>
                    </tr>
                    <tr style="text-align: left;">
                        <td style="width:21%; padding-left:10px;"><strong>Address: </strong>
                            {{ $customerAddress->shipping_address }}
                            {{ $customerAddress->shipping_city }}
                            {{ $customerAddress->shipping_state }}
                            {{ $customerAddress->shipping_pincode }}
                        </td>
                    </tr>
                </thead>
                
                <tbody>
                    
                    <tr>
                        <table class="detailed" style="border-left:none; border-right:none;">
                            <tr style="background: rgb(220 220 220 / 40%);font-weight: 600; height:35px;"  class="row-flex">
                                <td style="text-align: center; border-top:1px solid #000; border-bottom: 1px solid #000;  ">Sl No.<br></td>
                                <td style="border-top:1px solid #000; border-bottom: 1px solid #000; padding: 0 34.5px;">Description of Goods</td>
                                <td style="border-top:1px solid #000; border-bottom: 1px solid #000;  ">HSN/SAC</td>
                                <td style="text-align: right; border-top:1px solid #000; border-bottom: 1px solid #000;  ">Quantity</td>
                                <td style="text-align: right; border-top:1px solid #000; border-bottom: 1px solid #000;  ">Rate</td>
                                <td style="text-align: right; border-top:1px solid #000; border-bottom: 1px solid #000;  ">GST</td>
                                <!-- <td style="text-align: right; border-top:1px solid #000; border-bottom: 1px solid #000;  ">Disc. %</td> -->
                                <td style="text-align: right; border-top:1px solid #000; border-right: 0; border-bottom: 1px solid #000;">Amount</td>
                            </tr>
                            @if(!empty($orderItem))
                            @php $i=1; @endphp
                            @foreach($orderItem as $orderItm)
                            @php

                                $priceData = json_decode($orderItm->price_details);
                                $productDetails = json_decode($orderItm->product_details);
                                $hsnCode = $orderItm->unregistered_hsn_code;
                                $gstRate = 12;

                                if (isset($customerAddress->gst_number) && !empty($customerAddress->gst_number)) {
                                    $gstRate = 18;
                                    $hsnCode = $orderItm->registered_hsn_code;
                                }

                            @endphp
                            <tr class="border-top row-flex color_">
                                <td style="text-align: center; width: 35px;">{{ $i++ }}.</td>
                                <td style=" width: 171px;">
                                    {{ $orderItm->product_name }}
                                    {!! $productDetails !!}
                                    <p><strong>Binding: </strong>{{ $priceData->binding }}</p>
                                    
                                    @if(isset($priceData->split))
                                        <p><strong>Split: </strong>{{ $priceData->split }}</p>
                                    @endif

                                    <p><strong>Lamination: </strong>{{ $priceData->lamination }}</p>
                                    <p><strong>Cover: </strong>{{ $priceData->cover }}</p>
                                </td>
                                <td style="width: 56px;">{{ $hsnCode }}</td>
                                <td style="text-align: right; width: 47px;">{{ $orderItm->qty }}x{{ $orderItm->no_of_copies }}</td>
                                <td style="text-align: right; width: 41px; padding-right:5px; padding-left:0;">{{ $priceData->price }}</td>
                                <td style="text-align: right; width: 18px;">{{ $gstRate }}%</td>
                                <!-- <td style="text-align: right; width: 44px;">10%</td> -->
                                <td style="text-align: right; border-right:0;">{{ ($orderItm->qty*$priceData->price+($priceData->binding+$priceData->lamination+$priceData->cover))*$orderItm->no_of_copies }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </table>
                    </tr>
                    <tr>
                        <td>
                            <div style="display: flex;width: 99.6%;border-top: 1px solid gray;">
                            <table style="width: 57%;font-size: .8rem;border-right: none;border: none;">
                                <tbody style="padding: 0 38px 0 10px;">
                                
                                <tr>
                                    <td style="padding-left: 5px; opacity: 0;">Total In Words</td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px;"><b>Total</b></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px;">Amount Chargeable (in words)</td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px;">{{ amountToWords($order->paid_amount) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px; opacity:0;">Amount Chargeable (in words)</td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px;">Company's PAN :</td>
                                    <td style="padding-left: 5px;"><b>AAECI0809E</b></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px; color:transparent;">Company's PAN :</td>
                                    <td style="padding-left: 5px; color:transparent;"><b>AAECI0809E</b></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px; color:transparent;">Company's PAN :</td>
                                    <td style="padding-left: 5px; color:transparent;"><b>AAECI0809E</b></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px; color:transparent;">Company's PAN :</td>
                                    <td style="padding-left: 5px; color:transparent;"><b>AAECI0809E</b></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px; color:transparent;">Company's PAN :</td>
                                    <td style="padding-left: 5px; color:transparent;"><b>AAECI0809E</b></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px; color:transparent;">Company's PAN :</td>
                                    <td style="padding-left: 5px; color:transparent;"><b>AAECI0809E</b></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px; color:transparent;">Company's PAN :</td>
                                    <td style="padding-left: 5px; color:transparent;"><b>AAECI0809E</b></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px;">Declaration</td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px;">We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</td>
                                </tr>
                                </tbody>
                            </table>
                            <table style="width: 43%;border-left: none;font-size: .8rem;text-align: right;border-right: none;border-top: none; border-bottom:0;">
                                @if($isIntrastate)
                                <tr>
                                    <td style="padding-right: 30px;padding-left: 5px;padding-top: 5px;width: 175px;border-left: 1px solid gray; font-weight: 800;">CGST</td>
                                    <td style="padding-right: 5px;padding-left: 5px;padding-top: 5px;width: 103px;">{{ $gstCalc->cgst }}</td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 30px;padding-left: 5px;border-left: 1px solid gray; font-weight: 800;">SGST</td>
                                    <td style="padding-right: 5px;padding-left: 5px;">{{ $gstCalc->sgst }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td style="padding-right: 30px;padding-left: 5px;border-left: 1px solid gray; font-weight: 800;width: 175px;"><b>IGST</b></td>
                                    <td style="padding-right: 5px;padding-left: 5px;">{{ $gstCalc->igst }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding-right: 30px;padding-left: 5px;border-left: 1px solid gray; font-weight: 800;">Discount</td>
                                    <td style="padding-right: 5px;padding-left: 5px;">{{ $order->discount }}</td>
                                </tr>

                                @if($order->additional_discount)
                                <tr>
                                    <td style="padding-right: 30px;padding-left: 5px;border-left: 1px solid gray; font-weight: 800;">Add Discount</td>
                                    <td style="padding-right: 5px;padding-left: 5px;">{{ $order->additional_discount }}</td>
                                </tr>
                                @endif

                                <tr>
                                    <td style="padding-right: 30px;padding-left: 5px;border-left: 1px solid gray; font-weight: 800;">Shipping</td>
                                    <td style="padding-right: 5px;padding-left: 5px;">{{ $order->shipping }}</td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 30px;padding-left: 5px;border-left: 1px solid gray;border-bottom: 1px solid gray;"><b>S.Total</b></td>
                                    <td style="padding-right: 5px;padding-left: 5px;border-bottom: 1px solid gray;"><b>{{ ($order->paid_amount+$order->additional_discount)-$gstCalc->igst }}</b></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 30px;padding-left: 5px;border-left: 1px solid gray;border-bottom: 1px solid gray;"><b>G.Total</b></td>
                                    <td style="padding-right: 5px;padding-left: 5px;border-bottom: 1px solid gray;"><b>{{ $order->paid_amount }}</b></td>
                                </tr>
                                <tbody style="padding:15px 10px 10px; border-left: 1px solid gray;">
                                    <tr class="bank-row">
                                    <td style=" font-weight: 800;">E. & O.E</td>
                                    <td style="padding-right: 5px;padding-left: 5px;">&nbsp;
                                    </td>
                                </tr>
                                <tr class="bank-row">
                                    <td style=" font-weight: 800;">Company's Bank Details</td>
                                    <td style="padding-right: 5px;padding-left: 5px;">&nbsp;
                                    </td>
                                </tr>
                                <tr class="bank-row">
                                    <td style=" font-weight: 800;">A/c Holder's Name:</td>
                                    <td style="padding-right: 5px;padding-left: 5px;"><b>India Int-Tech Pvt. Ltd.</b>
                                    </td>
                                </tr>
                                <tr class="bank-row">
                                    <td style=" font-weight: 800;">Bank Name:</td>
                                    <td style="padding-right: 5px;padding-left: 5px;">HDFC BANK -7737
                                    </td>
                                </tr>
                                <tr class="bank-row">
                                    <td style=" font-weight: 800;">A/c No.:</td>
                                    <td style="padding-right: 5px;padding-left: 5px;">50200024207737
                                    </td>
                                </tr>
                                <tr class="bank-row">
                                    <td style=" font-weight: 800;">Branch & IFS Code:</td>
                                    <td style="padding-right: 5px;padding-left: 5px;">VIKAS MARG LAXMI NAGAR & HDFC0000120
                                    </td>
                                </tr>
                                <tr class="bank-row">
                                    <td style=" font-weight: 800;">SWIFT Code:</td>
                                    <td style="padding-right: 5px;padding-left: 5px;">&nbsp;
                                    </td>
                                </tr>

                                <tr style="display: block;">
                                    <td style="padding-top: 10px; font-weight: 800; display:block; text-align: right; padding-right:5px;">for India Int-Tech Pvt. Ltd.
                                    </td>
                                </tr>
                                <tr style="display: block;">
                                    <td style="padding-top: 10px; font-weight: 800; display:block; text-align: right; padding-right:5px;">Authorized Signature
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <!-- <table>
                                <tr>This is a Computer Generated Invoice</tr>
                            </table> -->
                        </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
</body>
</html>