<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Product Proposal Recalled</title>
    <style>
        /* -------------------------------------
                GLOBAL
        ------------------------------------- */
        * {
            margin: 0;
            padding: 0;
            font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            font-size: 100%;
            line-height: 1.6;
        }

        img {
            max-width: 100%;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100%!important;
            height: 100%;
            background-color: #EEEEEE;
        }

        hr {
            border: 0;
            height: 1px;
            background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));
            background-image:    -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));
            background-image:     -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));
            background-image:      -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));
        }


        /* -------------------------------------
                ELEMENTS
        ------------------------------------- */
        a {
            color: #348eda;
        }

        .btn-primary {
            text-decoration: none;
            color: #FFF;
            background-color: #1c3484;
            border: solid #1c3484;
            border-width: 5px 20px;
            line-height: 2;
            font-weight: bold;
            margin-right: 10px;
            text-align: center;
            cursor: pointer;
            display: inline-block;
            border-radius: 20px;
        }

        .btn-secondary {
            text-decoration: none;
            color: #FFF;
            background-color: #aaa;
            border: solid #aaa;
            border-width: 10px 20px;
            line-height: 2;
            font-weight: bold;
            margin-right: 10px;
            text-align: center;
            cursor: pointer;
            display: inline-block;
            border-radius: 25px;
        }

        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        .padding {
            padding: 10px 0;
        }


        /* -------------------------------------
                BODY
        ------------------------------------- */
        table.body-wrap {
            width: 100%;
            padding: 20px;
        }

        table.body-wrap .container {
            border: 1px solid #f0f0f0;
        }

        p.timeout-window {color:#ff0000;font-weight: bold;}

        /* -------------------------------------
                FOOTER
        ------------------------------------- */
        table.footer-wrap {
            width: 100%;
            clear: both!important;
        }

        .footer-wrap .container p {
            font-size: 12px;
            color: #666;

        }

        table.footer-wrap a {
            color: #999;
        }


        /* -------------------------------------
                TYPOGRAPHY
        ------------------------------------- */
        h1, h2, h3, h4, h5 {
            font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            line-height: 1.1;
            margin-bottom: 15px;
            color: #000;
            margin: 40px 0 10px;
            line-height: 1.2;
            font-weight: 200;
        }

        h1 {
            font-size: 36px;
        }
        h2 {
            font-size: 28px;
        }
        h3 {
            font-size: 16px;
        }

        p, ul, ol {
            margin-bottom: 10px;
            font-weight: normal;
            font-size: 14px;
        }

        p.remarks { margin-left: 10px;}

        ul li, ol li {
            margin-left: 5px;
            list-style-position: inside;
        }

        /* ---------------------------------------------------
                RESPONSIVENESS
                Nuke it from orbit. It's the only way to be sure.
        ------------------------------------------------------ */

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display: block!important;
            max-width: 600px!important;
            margin: 0 auto!important; /* makes it centered */
            clear: both!important;
        }

        /* Set the padding on the td rather than the div for Outlook compatibility */
        .body-wrap .container {
            padding: 20px;
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            max-width: 600px;
            margin: 0 auto;
            display: block;
        }

        /* Let's make sure tables in the content area are 100% wide */
        .content table {
            width: 100%;
        }

    </style>
</head>

<body bgcolor="#f6f6f6">

<!-- body -->
<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" bgcolor="#FFFFFF">

            <!-- content -->
            <div class="content">
                <table>
                    <tr>
                        <td>
                            <p>
                                <a href="{{ route('home') }}">
                                    <img src="{{ URL::asset('images/insight_logo_email.png') }}" alt="Insight Reporting"/>
                                </a>
                            </p>
                            <hr/>
                            <h3>Hello {{ $requestedBy }},</h3>
                            <p>This is to inform you that the following Product Proposal has been recalled by <strong>{{ $recalledBy }}</strong>.</p>

                            <ul>
                                <li><strong>Proposal ID:</strong> {{ $proposal_id }}</li>
                                <li><strong>Product Request ID:</strong> {{ $productRequest['request_id'] }}</li>
                                <li><strong>Product Name:</strong> {{ $proposal['product_name'] }}</li>
                            </ul>

                            @if(! empty($remarks))
                                <div>
                                    <h3>Note:</h3>
                                    <p><em>"{{$remarks}}"</em></p>
                                </div>
                                <br/>
                            @endif

                            <hr/>
                            <br/>

                            <p>You view this Product Proposal online, please click
                            <a href="{{route('product-proposals.show', [$productRequest['request_id'],  $proposal_id]) }}">here.</a></p>

                             <p>Alternatively, you can cut and paste the following link into your browser.</p>
                             <a href="{{route('product-proposals.show', [$productRequest['request_id'],  $proposal_id]) }} ">{{route('product-proposals.show', [$productRequest['request_id'],  $proposal_id]) }}</a></p>
                             <br/>
                             <br/>


                            <p>Regards,</p>
                            <p>The 36S Insight Team</p>


                        </td>
                    </tr>
                </table>
            </div>
            <!-- /content -->

        </td>
        <td></td>
    </tr>
</table>
<!-- /body -->

<!-- footer -->
<table class="footer-wrap">
    <tr>
        <td></td>
        <td class="container">

            <!-- content -->
            <div class="content">
                <table>
                    <tr>
                        <td align="center">
                            <p>Questions? <a href="mailto:customerservice@36-s.com"><unsubscribe>customerservice@36-s.com</unsubscribe></a>.
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
            <!-- /content -->

        </td>
        <td></td>
    </tr>
</table>
<!-- /footer -->

</body>
</html>

