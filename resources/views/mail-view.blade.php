<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
{{--    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">--}}
    <title>Document</title>
    <style>
        body{
            font-family: 'Quicksand', sans-serif;
        }
        .content{
            max-width: 600px;
            margin: auto;
            background: white;
        }
        .content-invoice-total .text-total {
            border-top: 1px solid #E5E7EB;
        }
        .bg-gray-200 {
            --tw-bg-opacity: 1;
            background-color: rgba(229,231,235, 0);
        }
        .heading-invoice-detail p{
            font-weight: 600;
            font-size: 1.25rem;
            line-height: 1.75rem;
        }
        .method-invoice-detail{
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(2,minmax(0,1fr));
            margin-top: 1.25rem;
            margin-bottom: 1.25rem;
        }
        .payment .heading-payment {
            font-weight: 600;
        }
        .payment .content-payment {
            font-size: .875rem;
            line-height: 1.25rem;
            margin-top: 0.25rem;
        }
        .shipping .heading-shipping {
            font-weight: 600;
        }
        .shipping .content-shipping {
            font-size: .875rem;
            line-height: 1.25rem;
            margin-top: 0.25rem;
        }
        .product-item {
            border-top: 1px solid #E5E7EB;
            border-bottom: 1px solid #E5E7EB;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            display: grid;
            grid-template-columns: repeat(4,minmax(0,1fr));
            gap: 1.5rem;
            margin-top: 0.5rem;
        }
        .product-item .image-product img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .product-item .content-product {
            grid-column: span 2/span 2;
        }
        .product-item .content-product p {
            font-weight: 500;
            font-size: 1.125rem;
            line-height: 1.75rem;
        }
        .product-item .content-product span {
            font-size: .875rem;
            line-height: 1.25rem;
        }
        .product-item .product-item-price {
            font-weight: 600;
            font-size: .875rem;
            line-height: 1.25rem;
            margin-top: 0.5rem;
        }
        .invoice-total {
            margin-top: 1.25rem;
        }
        .heading-invoice-total p{
            font-size: 1.25rem;
            line-height: 1.75rem;
            font-weight: 600;
        }
        .content-invoice-total{
            font-size: .875rem;
            line-height: 1.25rem;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(2,minmax(0,1fr));
        }
        .content-invoice-total .content-invoice-total-price p{
            font-weight: 600;
        }
        .content-invoice-total .content-invoice-total-price p span{
            font-weight: 400;
        }
    </style>
</head>
<body>
<div class="bg-gray-200">
    <div class="content p-5">
        <header>

        </header>
        <main>
            <div class="product-box">
                <div class="heading-invoice-detail">
                    <p>Hoa don chi tiet</p>
                </div>
                <div class="method-invoice-detail">
                    <div class="payment">
                        <div class="heading-payment">
                            Payment method
                        </div>
                        <div class="content-payment">
                            Paid via Etsy Payments on Nov 19, 2021
                        </div>
                    </div>
                    <div class="shipping">
                        <div class="heading-shipping">
                            Shipping address
                        </div>
                        <div class="content-shipping">
                            KEVIN HUANG
                            18638 alderbury dr
                            ROWLAND HEIGHTS, CA 91748
                            United States
                        </div>
                    </div>
                </div>

                <div class="product-item">
                    <div class="image-product">
                        <img src="https://picsum.photos/200/300"/>
                    </div>
                    <div class="content-product">
                        <p>Tra sua tran trau duong den</p>
                        <span>So luong : 2</span><br>
                        <span>{{rtrim("toping1,toping2,", ',')}}</span>
                    </div>
                    <div class="product-item-price">
                        {{number_format(50000)}} vnd
                    </div>
                </div>


            </div>
            <div class="invoice-total">
                <div class="heading-invoice-total">
                    <p>Tong hoa don</p>
                </div>
                <div class="content-invoice-total">
                    <div class="content-invoice-total-price">
                        <p>Tong san pham : <span>4</span></p>
                        <p>Tong gia : <span>100000</span></p>
                        <p>Sale : <span>0</span></p>
                        <p class="text-total">Tong hoa don : <span>100000</span></p>
                    </div>
                    <div></div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
