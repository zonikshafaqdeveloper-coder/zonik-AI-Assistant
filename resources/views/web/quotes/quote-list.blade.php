<script>
document.documentElement.style.opacity = "0"; 
</script>
@extends('web.layouts.app')
@section('content')



<style>


@media (max-width: 767px) {
    #outletTable thead {
        display: none !important;
    }
}

@media (min-width: 768px) {
    #outletTable thead {
        display: table-header-group !important;
    }
}

#priceConfirmModal .modal-content {
    padding: 20px 40px 20px 40px !important;
    border-radius: 24px !important;
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
}

#priceConfirmModal .modal-title
 {
    color: #121286;
    font-size: 25px;
    font-weight: 500;
}


#priceConfirmModal .modal-body {
    padding: 10px !important;
    background: #f0f0f0;
    margin-top: 15px;
    border-radius: 24px;
    margin-bottom: 15px;
}

.mobilecard-320 {
    border: 1px solid #ddd;
    border-radius: 12px;
    background: #fff;
    display: flex;
}

@media (max-width: 420px) {

   
    .mobile-image-320 {
        width: 100%;
        max-width: 80px;
        height: auto;
        border-radius: 8px;
    }

    
    .mobile-details-320 {
        padding-left: 6px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .mobile-details-320 h5 {
        font-size: 13px;
        line-height: 16px;
        margin-bottom: 4px;
        word-break: break-word;
    }

    .mobile-details-320 p {
        font-size: 12px;
        margin-bottom: 6px;
        line-height: 14px;
    }

 
    .mobile-action-row-320 {
        display: flex;
        align-items: center;
        width: 100%;
        justify-content: space-between;
        flex-wrap: nowrap;
    }

    
    .mobile-input-320 {
        width: 60%;
        font-size: 11px;
        padding: 4px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

   
    .mobile-remove-btn-320 {
        padding: 4px 6px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        flex-shrink: 0;
        min-width: 32px;
        height: 32px;
    }
}


@media (max-width: 360px) {

    .mobile-input-320 {
        width: 58%;
    }

    .mobile-remove-btn-320 {
        min-width: 30px;
        height: 30px;
    }
}

.mobile-card {
  background: #fff;
  padding: 12px;
  border-radius: 10px;
  transition: all 0.2s ease-in-out;
  position: relative;
}

.mobile-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}


.product-img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border: 1px solid #eee;
  border-radius: 8px;
  flex-shrink: 0;
}


.ribbon-2 {
  --f: 8px;
  --r: 10px;
  --t: 0px;
  position: absolute;
  inset: var(--t) calc(-1 * var(--f)) auto auto;
  padding: 0px 10px var(--f) calc(6px + var(--r));
  clip-path: polygon(
    0 0,
    100% 0,
    100% calc(100% - var(--f)),
    calc(100% - var(--f)) 100%,
    calc(100% - var(--f)) calc(100% - var(--f)),
    0 calc(100% - var(--f)),
    var(--r) calc(50% - var(--f) / 2)
  );
  background: #121286;
  box-shadow: 0 calc(-1 * var(--f)) 0 inset rgba(0, 0, 0, 0.2);
  font-size: 11px;
  color: #fff;
  height: 30px;
  line-height: 22px;
  font-weight: 600;
  /*z-index: 5;*/
  text-align: center;
}


.product-title {
  font-size: 13px;
  font-weight: 600;
  color: #222;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  line-height: 1.3;
  max-height: 2.6em;
  word-break: break-word;
  white-space: normal;
  margin-bottom: 4px;
}


.price-text {
  font-size: 12px;
  color: #333;
  line-height: 1.2;
  margin-bottom: 4px;
}


.icon-btn {
  background-color: #121286;
  height: 32px;
  width: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  border: none;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.icon-btn:hover {
  background-color: #9135b5;
  transform: scale(1.05);
}


.mobile-card .d-flex {
  gap: 10px;
  align-items: flex-start;
  flex-wrap: nowrap;
}


@media (max-width: 360px) {
  .mobile-card {
    padding: 10px;
  }

  .product-img {
    width: 70px;
    height: 70px;
  }

  .ribbon-2 {
    --f: 7px;
    --r: 8px;
    height: 26px;
    font-size: 10px;
    line-height: 20px;
  }

  .product-title {
    font-size: 12px;
    line-height: 1.25;
    -webkit-line-clamp: 2;
    max-height: 2.5em;
  }

  .price-text {
    font-size: 11px;
  }

  .icon-btn {
    height: 28px;
    width: 28px;
    font-size: 12px;
  }

  .mobile-card .d-flex {
    gap: 8px;
    flex-direction: row;
    align-items: flex-start;
  }
}


@media (min-width: 361px) and (max-width: 425px) {
  .product-img {
    width: 80px;
    height: 80px;
  }

  .product-title {
    font-size: 13px;
  }

  .price-text {
    font-size: 12px;
  }

  .icon-btn {
    height: 32px;
    width: 32px;
  }
}


@media (min-width: 576px) {
  .mobile-card {
    padding: 14px;
  }

  .product-img {
    width: 90px;
    height: 90px;
  }

  .ribbon-2 {
    --f: 15px;
    --r: 12px;
    height: 38px;
    font-size: 13px;
    padding: 0px 13px var(--f) calc(6px + var(--r));
  }

  .product-title {
    font-size: 14px;
    -webkit-line-clamp: 2;
  }

  .price-text {
    font-size: 13px;
  }

  .icon-btn {
    height: 36px;
    width: 36px;
  }
}



@media (min-width: 768px) {
  #searchInput {
    width: 25% !important;
  }
}
@media (max-width: 767.98px) {
  #searchInput {
    width: 100% !important;
  }
}

/*.ribbon-2 {*/
/*    --f: 15px;*/
/*    --r: 12px;*/
/*    --t: 47px;*/
/*    position: absolute;*/
/*    inset: var(--t) calc(-1* var(--f)) auto auto;*/
/*    padding: 0px 13px var(--f) calc(6px + var(--r));*/
/*    clip-path: polygon(0 0, 100% 0, 100% calc(100% - var(--f)), calc(100% - var(--f)) 100%, calc(100% - var(--f)) calc(100% - var(--f)), 0 calc(100% - var(--f)), var(--r) calc(50% - var(--f) / 2));*/
    /*background: #a558c8;*/
/*    background: #121286;*/
/*    box-shadow: 0 calc(-1* var(--f)) 0 inset #0005;*/
/*    font-size: 14px;*/
/*    height: 40px;*/
/*    color: #fff;*/
/*    line-height: 24px;*/
/*}*/

.flag-discount {
    border-radius: 6px 0 0 6px;
    color: #fff;
    display: block;
    float: left;
    margin-left: 8rem;
    padding: 0px 6px;
    background: #0d6efdd1;
    font-size: 15px;
    font-weight: 400;
    position: relative;
    margin-top: -5rem;

}
.flag-discount::before,
.flag-discount::after {
    content: "";
    position: absolute;
    left: 100%;
    width: 0;
    height: 0;
    border-style: solid;
    display: block;
}
.flag-discount::before {
    top: 0;
    border-width: 22px 15px 0 0;
    border-color: #0d6efdd1 transparent transparent transparent;
}
.flag-discount::after {
    bottom: 0;
    border-width: 0 15px 22px 0;
    border-color: transparent transparent #0d6efdd1 transparent;
}

/* Custom modal width for desktop and mobile */
.custom-modal-width {
    width: 100%; /* Default for mobile */
    max-width: 100%; /* Ensures it does not overflow */
    margin: auto; /* Centers the modal */
}

/*.pt-56{*/
/*        padding-top: 55px;*/
/*    }*/

@media (min-width: 768px) {
    .custom-modal-width {
        width: 70%; /* 70% for desktop */
        max-width: 70%; /* Ensures it doesn't exceed 70% */
    }
    .pt-56{
        padding-top: 10px;
    
    }
}

.coupon-description{
    font-size: small;
}
.dispay-content{
    display: contents;
}

.float-right{
float: right;
}
b, strong {
    font-weight: bold !important;
}

    .couponList li{
        border: 1px solid #f1f1f1;
        padding: 10px 12px
    }
    .margin-t{
        margin-top:25px;
    }

    .p-all{
        padding: 25px;
    }

    .product-n{
        font-size: 15px !important;
        font-weight: 500 !important;
    }

    td p{
        width: 250px
    }

    .section{
        padding-top: 0;
        padding-bottom: 0;
    }

    .brk-line{
        margin: 0px;
        padding: 0px;
        width: 100%;
        box-sizing: border-box;
        height: 0.1rem;
        color: #000;
        /* color: rgb(244, 244, 244); */
        /* background-color: rgb(244, 244, 244); */
        border: medium;
    }

    .card1{
        padding: 15px;
        background-color:#F4F6FB !important;
        scroll-margin-top: 194px;
        margin-bottom: 24px;
        box-shadow: 0 1px 4px 0 rgba(28,28,28,.06);
        border-radius: 24px;
        border:2px solid #E4EBFC !important;
    }

 .accordion-item {
      margin-bottom: 10px;
    }
    .accordion-header {
      cursor: pointer;
    }
    .accordion-body {
      display: none;
    }
    tr td {
    vertical-align: middle;
    font-family: system-ui;
    
    }
    
    th td{
        text-align: left;
    }
    .card-h{
        color: var(--coolgreycoolgrey-600,#767c8f);
        font-size: 22px;
        font-style: normal;
        font-weight: 700;
        text-transform: capitalize;
        line-height: 24px;
    }
    /* --------------------------------- */

    .btn-w{
        width : fit-content ;
    }

    .tabs1, .tabs2 {
        position: relative;
    }

    .countSpan {
        /*background: #a558c8;*/
        background: #e97457;
        color: white;
        padding: 1px 7px;
        position: absolute;
        top: -5px; /* Adjust this value to change the vertical position */
        right: -5px; /* Adjust this value to change the horizontal position */
        border-radius: 30%; /* Optional: For a rounded shape */
    }

    .modal-w {
        width: 500px;
    }

    .modal-h {
        font-size: 17px;
        letter-spacing: 0.2;
        font-weight: 500;
    }

    /* -----------------quotelist---------------- */

    .q-tab-w {
        width: 225px !important;
    }

    .tabs1 {
        width: 230px ;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        border: 2px solid #a558c8;
    }
    .tabs2 {
        width: 230px ;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        border: 2px solid #a558c8;
    }

    .w-input {
        width: 150px !important;
    }

    .table-c {
      background-color: #ffffff;
    color: #151515;
    border: 1px solid #ccc;
    }

    .w11 input[type='text'] {
        border-radius: 2px !important;
        border: 1px solid #979797 !important;
        box-sizing: border-box !important;
        padding: 1px !important;
        width: 30% !important;
    }

    .nav {
        text-align: center;
    }

    .orders-m .nav-link {
        text-align: left !important;
        letter-spacing: 0.5px;
        font-weight: 500;
        font-size: 15px;
        font-family: var(--secondary-font) !important;
        border-radius: 0px;
        margin-left: 0px;
        color: #2B2B2B;
        padding: 20px 20px;
    }

    .ml-8 {
        margin-left: 80px;
    }

    .nav {
        text-align: center !important;
    }

    .svg-img {
        margin-right: 10px;
        height: 20px;
    }

    .orders-tab h4 {
        font-size: 25px;
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    .orders-tab-margin {
        margin: auto  15px;
    }

    .img-div h4 {
        font-size: 20px;
        font-weight: 500;
        letter-spacing: 0.5px;
        color: #585858;
    }

    .img-div h5 {
        color: #959595;
        letter-spacing: 0.5px;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    .quantity__minus,
    .quantity__plus {
        display: block;
        width: 22px;
        height: 23px;
        margin: 0;
        background: #FFB5B5;
        text-decoration: none;
        text-align: center;
        line-height: 23px;
    }

    .quantity__minus:hover,
    .quantity__plus:hover {
        background: #575b71;
        color: #fff;
    }

    .quantity__minus {
        border-radius: 15px;
    }

    .quantity__plus {
        border-radius: 15px;
    }

    .quantity__input {
        width: 24px !important;
        height: 19px;
        margin: 0;
        padding: 0 !important;
        text-align: center;
        /* border-top: 2px solid #dee0ee;
    border-bottom: 2px solid #dee0ee;
    border-left: 1px solid #dee0ee;
    border-right: 2px solid #dee0ee; */
        background: #fff;
        color: #151515;
        border: 0px !important;
    }

    .optional {
        padding: 0px !important;
    }

    .quantity__minus:link,
    .quantity__plus:link {
        color: #BD2F2F;
        background: #FFB5B5;
        font-size: 20px;
        font-weight: 600;
    }

    .quantity__minus:visited,
    .quantity__plus:visited {
        color: #fff;
    }

    .requested-enquiry tbody tr td {
        font-size: 15px;
        font-weight: 500;
        margin-right: 10px;
        border-bottom: 0px;

    }

    .requested-enquiry tbody tr,
    .requested-enquiry thead tr {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        margin: 0px 0px 10px 0px;
        background-color: #ffffff;
        border-radius: 5px;
        padding: 10px;
        border-bottom: 0px !important;
    }

    .requested-enquiry {
        box-shadow: inset 6px 6px 8px #dadada, inset -6px -6px 8px #dfdfdf;
        padding: 20px;
        display: block;
        border-radius: 10px;
        background: #ECF0F1;
    }

    .enquiry-img {
        height: 90px;
        width: 90px;
        /* object-fit: cover; Ensures the image scales proportionally and covers the set area */
        border: 1px solid #ddd; /* Optional border for better visuals */
        border-radius: 5px; 
    }

    th {
        border-bottom: 0px;
    }


    .del-svg {
        height: 60px;
        width: 60px;
    }

    td:first-child,
    th:first-child {
        border-radius: 10px 0 0 10px;
        padding: 10px;
        text-align: center;
        font-weight: bold !important;
    }

    .fa-trash {
        /*background-color: #a558c8;*/
        background-color: #e97457;
        height: 40px;
        width: 40px;
        text-align: center;
        line-height: 40px;
        border-radius: 50px;
        font-size: 16px;
        color: #ffffff;
    }

    td:last-child,
    th:last-child {
        border-radius: 0 5px 5px 0;
        padding: 10px;
    }

    .scrollable-element {
        scrollbar-width: thin;
    }

    ::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }

    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        -webkit-border-radius: 10px;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        -webkit-border-radius: 10px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.3);
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
    }

    ::-webkit-scrollbar-thumb:window-inactive {
        background: rgba(255, 255, 255, 0.3);
    }

    .bg-blue {
        background-color: #F4F6FB;
    }

    .home-demo .item {
        background: #ff3f4d;
    }

    .rightside1 {
        width: calc(100% - 260px) !important;
    }

    .discount-div {
        background-color: #2461A8;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
    }

    .discount-divh {
        font-size: 20px;
        letter-spacing: 1px;
        font-weight: 500 !important;
    }

    .discountc .carousel-control-next-icon {
        position: relative;
    }

    .quantity {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        padding: 0;
        width: fit-content;
        /*border: 1px solid #a558c8;*/
        border: 1px solid #121286;
    }

    .
    .quantity {

        width: .quantity:width;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .tr {
        box-shadow: none !important;
        margin: 0px 0px 10px 0px;
        display: block;
        background-color: transparent !important;
        border-radius: 5px;
        padding: 10px;
        border-bottom: 0px !important;
    }

    .orders-m .nav-link {
        text-align: left !important;
        letter-spacing: 0.5px;
        font-weight: 500;
        font-size: 15px;
        font-family: var(--secondary-font) !important;
        border-radius: 0px;
        margin-left: 0px;
        color: #2B2B2B;
        padding: 20px 20px;
        display: flex;
        align-items: center;
    }

    .profile-arrow {
        color: #a558c8;
        text-align: right;
        width: 190px;
        position: absolute;
        font-size: 20px;
    }

    .orders-m .nav-link .left-icon .active {
        color: #2B2B2B;
        background-color: #FFF2F2;
        font-weight: 500;
        font-size: 15px;
        font-family: var(--secondary-font) !important;
        border-radius: 0px;
        margin-left: 0px;

    }

    .svg-img {
        margin-right: 10px;
        height: 20px;
    }

    .orders-tab h4 {
        font-size: 25px;
        letter-spacing: 0.5px;
        font-weight: 500;
    }



    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .tr {
        box-shadow: none !important;
        margin: 0px 0px 10px 0px;
        display: block;
        background-color: transparent !important;
        border-radius: 5px;
        padding: 10px;
        border-bottom: 0px !important;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    .quantity__minus,
    .quantity__plus {
        display: block;
        width: 22px;
        height: 23px;
        margin: 2px;
        font-weight: 600;
        color: #fff;
        /*background: #c699dd;*/
        background: #121286;
        text-decoration: none;
        text-align: center;
        line-height: 23px;
        border: 0px !important;
    }

    .quantity__minus:hover,
    .quantity__plus:hover {
        background: #575b71;
        color: #fff;
    }

    .optional {
        padding: 0px !important;
    }

    .quantity__minus:link,
    .quantity__plus:link {
        color: #BD2F2F;
        background: #FFB5B5;
        font-size: 20px;
        font-weight: 600;
    }

    .quantity__minus:visited,
    .quantity__plus:visited {
        color: #fff;
    }


    table {
        /* border-collapse: separate; */
        border-spacing: 0 1em;
    }


    th {
        border-bottom: 0px;
    }

    .del-svg {
        height: 60px;
        width: 60px;
    }

    td:first-child,
    th:first-child {
        border-radius: 10px 0 0 10px;
        padding: 10px;
    }

    .fa-trash,
    .fa-cart-shopping1 {
        /*background-color: #a558c8;*/
        background-color: #e97457;
        height: 40px;
        width: 40px;
        text-align: center;
        line-height: 40px;
        border-radius: 50px;
        font-size: 16px;
        color: #ffffff;
    }
    
    
    
.icon-btn {
  background-color: #a558c8; /* Purple */
  height: 40px;
  width: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  padding: 0;
}

.icon-btn i {
  font-size: 16px;
  color: #ffffff;
}

    td:last-child,
    th:last-child {
        border-radius: 0 5px 5px 0;
        padding: 10px;
    }

    .scrollable-element {
        scrollbar-width: thin;
    }

    ::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }

    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        -webkit-border-radius: 10px;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        -webkit-border-radius: 10px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.3);
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
    }

    ::-webkit-scrollbar-thumb:window-inactive {
        background: rgba(255, 255, 255, 0.3);
    }

    .bg-blue {
        background-color: #F4F6FB;
    }

    .home-demo .item {
        background: #ff3f4d;
    }

    .rightside1 {
        width: calc(100% - 260px) !important;
    }

    .discount-div {
        background-color: #2461A8;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
    }

    .discount-divh {
        font-size: 20px;
        letter-spacing: 1px;
        font-weight: 500 !important;
    }

    .discountc .carousel-control-next-icon {
        position: relative;
    }

    .discountc .carousel-control-next-icon {
        background-image: url("/assets/images/right-arrow.png");
        opacity: 1 !important;

    }

    .discountc .carousel-control-prev {
        display: none;
    }

    .discount-div1 {
        background-color: #168848;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
    }

    .discount-div2 {
        background-color: #2824A8;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
    }

    .cart-table1 .container .row1 {
        box-shadow: inset 6px 6px 8px #dadada, inset -6px -6px 8px #dfdfdf;
        border-radius: 10px;
    }

    .cart-table1 .container .row1 .col-md-8 table tr {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        margin: 0px 0px 10px 0px;
        display: block;
        background-color: #ffffff;
        border-radius: 5px;
        padding: 10px;
        border-bottom: 0px !important;
    }

    .cart-table1 .container .row1 .col-md-8 table tr td {
        border-bottom: 0px !important;
        font-size: 15px;
        font-weight: 500;
        margin-right: 10px;
        border-bottom: 0px;
    }


    .cart-right {
        background-color: #F4F6FB;
        border: 2px solid #E4EBFC;
        padding: 0px 25px 15px 25px !important;
    }

    .cart-right h4 {
        /*color: #a558c8;*/
        color: #121286;
        font-weight: 500;
        letter-spacing: 0.5px;
        font-size: 20px;
    }


    .cart-right .text-end1 {
        display: flex;
        align-items: end;
        justify-content: end;
    }

    .color-dark {
        color: #212529;
    }



    .order-summary .row .col-md-8 p,
    .order-summary .row .col-md-4 p {
        font-weight: 500;
        letter-spacing: 0.5px;
        padding-bottom: 15px;
    }

    .secondary-color {
        /*color: #a558c8;*/
        color: #121286;
    }

    .order-row {
        border-bottom: 1px solid #ccc;
    }

    .taxes {
        font-size: 12px;
    }

    .dic-div {
        background-color: #D5EBE4;
        text-align: center;
        padding: 5px;
        font-weight: 500;
        letter-spacing: 0.5px;
        border-radius: 10px;

    }

    .discount-div {
        background-color: #2461A8;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
    }

    .discount-divh {
        font-size: 20px;
        letter-spacing: 1px;
        font-weight: 500 !important;
    }

    .discountc .carousel-control-next-icon {
        position: relative;
    }

    .discountc .carousel-control-next-icon {
        background-image: url("/assets/images/right-arrow.png");
        opacity: 1 !important;

    }

    .discountc .carousel-control-prev {
        display: none;
    }

    .discount-div1 {
        background-color: #168848;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
    }

    .discount-div2 {
        background-color: #2824A8;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
    }

    .cart-table1 .container .row1 {
        box-shadow: inset 6px 6px 8px #dadada, inset -6px -6px 8px #dfdfdf;
        border-radius: 10px;
    }

    .cart-table1 .container .row1 .col-md-8 table tr {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        margin: 0px 0px 10px 0px;
        display: block;
        background-color: #ffffff;
        border-radius: 5px;
        padding: 10px;
        border-bottom: 0px !important;
    }

    .cart-table1 .container .row1 .col-md-8 table tr td {
        border-bottom: 0px !important;
        font-size: 15px;
        font-weight: 500;
        margin-right: 10px;
        border-bottom: 0px;
    }

    .ct1 {
        width: 5%;
    }

    .ct2 {
        width: 50%;
    }

    .ct3 {
        width: 15%;
    }


    .ct5 {
        width: 25%;
    }

    .cart-right {
        background-color: #F4F6FB;
        border: 2px solid #E4EBFC;
        padding: 20px;
        border-radius: 5px;
    }



    .cart-right h3 {
        /*color: #a558c8;*/
        color: #121286;
        font-weight: 500;
        letter-spacing: 0.5px;
        font-size: 18px;
    }

    .cart-right .text-end1 {
        display: flex;
        align-items: end;
        justify-content: end;
    }

    .color-dark {
        color: #212529;
    }


    .order-summary .row .col-md-8 p,
    .order-summary .row .col-md-4 p {
        font-weight: 500;
        letter-spacing: 0.5px;
        padding-bottom: 15px;
    }

    .secondary-color {
        color: #a558c8;
    }

    .order-row {
        border-bottom: 1px solid #ccc;
    }

    .taxes {
        font-size: 12px;
    }

    .dic-div {
        background-color: #D5EBE4;
        text-align: center;
        padding: 5px;
        font-weight: 500;
        letter-spacing: 0.5px;
        border-radius: 10px;

    }



    /*  */
    @import  "./variable.css";

    /* header */
    .logo {
        height: 20%;
    }

    .header-section {
        padding: 20px;
        height: 100px;
    }

    .header-section .container .row .col-md-6 .shadow-inner-btn {
        position: relative;
        z-index: 2;
    }

    /* banner */
    .banner-section {
        position: relative;
        height: calc(100vh - 100px);
        display: flex;
        align-items: center;
    }

    .banner-img {
        position: absolute;
        top: -100px;
    }

    .banner-left {
        margin-left: 110px;
    }

    .header .top-nav {
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px !important;
    }

    .banner-section h1 {
        font-family: var(--secondary-font);
        text-transform: uppercase;
        line-height: 55px;
        letter-spacing: 0.5px;
        font-size: 35px;
    }

    .features-div .row .col-md-3 img {
        height: 120px;
        margin-top: -80px;
        margin-left: -50px;
    }

    .features-div {
        padding: 20px 10px;
        border: 2px solid #303030;
        border-style: dashed;
    }

    .features-section {
        padding: 60px 0px;
        background-color: #F5F8FF;
    }

    /* Steps */
    .image {
        position: absolute;
        width: 650px;
        height: 650px;
        margin: -60px 0 0 -60px;
        margin: -60px 0 0 -60px;
        -webkit-animation: spin 25s linear infinite;
        -moz-animation: spin 25s linear infinite;
        animation: spin 25s linear infinite;
    }

    @-moz-keyframes spin {
        100% {
            -moz-transform: rotate(360deg);
        }
    }

    @-webkit-keyframes spin {
        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes  spin {
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    .step-section {
        padding: 100px 0px;
    }

    .mob-div img {
        height: 400px;
        position: relative;
        margin-top: 50px;
    }

    .points ul {
        list-style-type: none;
    }

    .points ul li {
        display: flex;
        align-items: center;
        font-size: 20px;
        letter-spacing: 1px;
        font-weight: 500;
        margin-bottom: 30px;

    }

    .num {
        background-image: linear-gradient(to right, #a558c8, #D01919D6);
        color: #fff;
        height: 55px;
        width: 55px;
        display: block;
        border-radius: 50px;
        text-align: center;
        line-height: 55px;
        font-size: 25px;
        font-weight: 500;
    }

    .btn-bar {
        border-radius: 50px;
        border: none;
        background: #f8f8f8;
        box-shadow: inset 6px 6px 8px #dadada, inset -6px -6px 8px #dfdfdf;
        padding: 10px 30px;
        padding-left: 30px;
        font-family: var(--secondary-font) !important;
        letter-spacing: 1px;
        font-size: 16px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .products-section {
        padding: 100px 0px;
        background-color: #F5F8FF;
    }

    /* Products */
    .beverages-img {
        height: 35px;
        margin-right: 10px;
    }

    .btn-bar ul li {
        list-style-type: none;

    }

    .btn-bar {
        width: 100%;
    }

    .btn-bar ul .ml {
        margin-left: 40px;
    }

    .fa-check {
        background-color: #1fa135;
        height: 40px;
        width: 40px;
        text-align: center;
        line-height: 40px;
        border-radius: 50px;
        font-size: 16px;
        color: #ffffff;
        border: none;
        font-size: 20px;
    }

    .tick,
    .cross, .money {
        border: none;
        background-color: #fff;

    }



    .red-btn1 {
        background-color: var(--primary);
        padding: 6px 21px;
        color: #fff !important;
        border-radius: 5px;
        font-size: 14px;
        letter-spacing: 0.5px;
        font-weight: 500;
        font-family: var(--secondary-font);
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    }

    .fa-xmark {
        background-color: #ce6e6e;
        height: 40px;
        width: 40px;
        text-align: center;
        line-height: 40px;
        border-radius: 50px;
        font-size: 16px;
        color: #ffffff;
        border: none;
        font-size: 20px;
    }

    .fa-money-check {
        background-color: #006b79;
        height: 40px;
        width: 40px;
        text-align: center;
        line-height: 40px;
        border-radius: 50px;
        font-size: 16px;
        color: #ffffff;
        border: none;
        font-size: 20px;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: #fff;
        background-color: var(--primary) !important;
        border-radius: 0px !important;
    }

    .nav-pills .nav-link {
        background: 0 0;
        border: 0;
        border-radius: .25rem;
        color: var(--dark);
        font-weight: 500;
        letter-spacing: 0.5px;
        height: 50px;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .product-div {
        background-color: #fff;
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        padding: 20px;
        border-radius: 5px;

    }

    .product-main-div {
        padding: 40px 0px;
    }

    /* Logo Slider */
    .slick-slide {
        margin: 0px 20px;
    }

    .slick-slide img {
        width: 100%;
    }

    .slick-slider {
        position: relative;
        display: block;
        box-sizing: border-box;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-touch-callout: none;
        -khtml-user-select: none;
        -ms-touch-action: pan-y;
        touch-action: pan-y;
        -webkit-tap-highlight-color: transparent;
    }

    .slick-list {
        position: relative;
        display: block;
        overflow: hidden;
        margin: 0;
        padding: 0;
    }

    .slick-list:focus {
        outline: none;
    }

    .slick-list.dragging {
        cursor: pointer;
        cursor: hand;
    }

    .slick-slider .slick-track,
    .slick-slider .slick-list {
        -webkit-transform: translate3d(0, 0, 0);
        -moz-transform: translate3d(0, 0, 0);
        -ms-transform: translate3d(0, 0, 0);
        -o-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
    }

    .slick-track {
        position: relative;
        top: 0;
        left: 0;
        display: block;
    }

    .slick-track:before,
    .slick-track:after {
        display: table;
        content: '';
    }

    .slick-track:after {
        clear: both;
    }

    .slick-loading .slick-track {
        visibility: hidden;
    }

    .slick-slide {
        display: none;
        float: left;
        height: 100%;
        min-height: 1px;
    }

    [dir='rtl'] .slick-slide {
        float: right;
    }

    .slick-slide img {
        display: block;
    }

    .slick-slide.slick-loading img {
        display: none;
    }

    .slick-slide.dragging img {
        pointer-events: none;
    }

    .slick-initialized .slick-slide {
        display: block;
    }

    .slick-loading .slick-slide {
        visibility: hidden;
    }

    .slick-vertical .slick-slide {
        display: block;
        height: auto;
        border: 1px solid transparent;
    }

    .slick-arrow.slick-hidden {
        display: none;
    }

    .padding-100 {
        padding: 100px 0px;
    }

    .home-demo .item {
        background: #ff3f4d;
    }

    .home-demo h2 {
        color: #FFF;
        text-align: center;
        padding: 5rem 0;
        margin: 0;
        font-style: italic;
        font-weight: 300;
    }

    .testimonial-div {
        padding: 20px;
        background-color: #ECF1FD;
        border: 2px solid #D8E3FF;
        border-radius: 5px;
    }

    .testimonial-div p {
        text-align: justify;
    }

    .testimonial-div h5 {
        font-size: 22px;
    }

    .testimonial-div h6 {
        font-size: 18px;
    }

    .quote {
        text-align: end;
    }

    .quote img {
        height: 50px;
        width: 50px !important;
    }

    .testimonial-div-red {
        background-color: #a558c8;
        border: 2px solid #7c1b1b;
        padding: 20px;
        border-radius: 5px;
    }

    .testimonial-div-red p {
        color: #ffffff;
    }

    .testimonial-div-red h5 {
        color: #ffffff;
    }

    .testimonial-div-red h6 {
        color: #dadada !important;
    }

    .testimonial .owl-carousel .owl-stage-outer .owl-nav {
        position: relative;
    }

    .testimonial .owl-carousel .owl-stage-outer .owl-nav .owl-next span {
        background-image: linear-gradient(to right, #131313, #202020d6);
        color: #fff;
        height: 45px;
        width: 45px;
        display: block;
        border-radius: 50px;
        text-align: center;
        line-height: 40px;
        font-size: 45px;
        font-weight: 500;
    }

    .testimonial .owl-carousel .owl-stage-outer .owl-nav .owl-prev span {
        background-image: linear-gradient(to right, #a558c8, #D01919D6);
        color: #fff;
        height: 45px;
        width: 45px;
        display: block;
        border-radius: 50px;
        text-align: center;
        line-height: 40px;
        font-size: 45px;
        font-weight: 500;
    }

    .testimonial .owl-carousel .owl-stage-outer .owl-nav .owl-prev {
        position: absolute;
        top: -160px;
        left: -80px;
    }

    .accordion-item {
        margin-bottom: 20px;
        margin-bottom: 20px;
        border-radius: 10px !important;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        background-color: #fff;
    }

    .accordion-button:focus {
        z-index: 3;
        border-color: #fff;
        outline: 0;
        box-shadow: 0 0 0 .25rem rgba(255, 255, 255, 0.25);
    }

    .accordion-button:not(.collapsed) {
        color: #fff;
        background-color: #a558c8;
    }

    .accordion-item button {
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .hr {
        /*background-image: linear-gradient(to right, #a558c8, #AEAEAE17);*/
        background-image: linear-gradient(to right, #e97457, #aeaeae00);
        width: 160px;
        height: 3px;
    }

    .social i {
        /*background-image: linear-gradient(to right, #a558c8, #D01919);*/
        background-image: linear-gradient(to right, #e97457, #e974579c);
        color: #fff;
        height: 40px;
        width: 40px;
        line-height: 40px;
        border-radius: 50px;
        text-align: center;
    }

    .social p {
        margin-left: 10px;
        color: #cecece;
        letter-spacing: 0.5px;
    }

    .links ul .lir {
        list-style-type: none;
        margin-right: 10px;
    }

    .links ul {
        list-style-type: none;
    }

    .links ul li {
        color: #cecece;
        letter-spacing: 0.5px;
    }

    .text-light {
        color: #ffffff !important;
    }

    .social-links ul {
        list-style-type: none;
    }

    .so-l {
        font-size: 20px;
        margin-right: 10px;
    }

    .div-hr {
        width: 80%;
        background-color: #414141;
        height: 2px;
        margin: 0 auto;
        margin-top: 50px;
    }

    .footer-section {
        padding: 100px 0px 50px 0px;
    }

    .ml {
        margin-left: 20px !important;
    }

    .mr {
        margin-right: 20px !important;
    }

    .copyright-sec .mr-2 {
        margin-right: 20px;
    }

    .copyright-sec .ml-2 {
        margin-left: 20px;
    }

    .copyright-sec img {
        height: 50px;
    }

    .heading {
        font-size: 40px;
        font-weight: 500 !important;
        text-transform: uppercase;

    }

    .product-quantity>* {
        width: 36.9px;
    }

    .product-quantity>input[type="number"]::-webkit-inner-spin-button,
    .product-quantity>input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .product-quantity>input {
        border: none;
        text-align: center;
        font-size: 12px;
    }

    .product-quantity {
        display: inline-flex;
        border: 1px solid #e6e6e6;
        align-items: center;
        height: 40px;
        border-radius: 4px;
    }

    .product-quantity-plus:before,
    .product-quantity-minus:before {
        width: 11px;
        display: block;
        margin: 0 auto;
    }

    .product-quantity-plus:before {
        content: "+";
    }

    .product-quantity-minus:before {
        content: "-";
    }

    .product-quantity-plus,
    .product-quantity-minus {
        cursor: pointer;
    }

    .product-quantity-plus:before,
    .product-quantity-minus:before {
        width: 11px;
        display: block;
        margin: 0 auto;
    }



    .discount-div {
        background-color: #2461A8;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
    }

    .discount-divh {
        font-size: 20px;
        letter-spacing: 1px;
        font-weight: 500 !important;
    }

    .discountc .carousel-control-next-icon {
        position: relative;
    }

    .discountc .carousel-control-next-icon {
        background-image: url("/assets/images/right-arrow.png");
        opacity: 1 !important;

    }

    .discountc .carousel-control-prev {
        display: none;
    }

    .discount-div1 {
        background-color: #168848;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
    }

    .discount-div2 {
        background-color: #2824A8;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
    }

    .cart-table1 .container .row1 {
        box-shadow: inset 6px 6px 8px #dadada, inset -6px -6px 8px #dfdfdf;
        border-radius: 10px;
    }

    .cart-table1 .container .row1 .col-md-8 table tr {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        margin: 0px 0px 10px 0px;
        display: block;
        background-color: #ffffff;
        border-radius: 5px;
        padding: 10px;
        border-bottom: 0px !important;
    }

    .cart-table1 .container .row1 .col-md-8 table tr td {
        border-bottom: 0px !important;
        font-size: 15px;
        font-weight: 500;
        margin-right: 10px;
        border-bottom: 0px;
    }

    .ct1 {
        width: 5%;
    }

    .ct2 {
        width: 50%;
    }


    .ct4 {
        width: 15%;
    }

    .ct5 {
        width: 30%;
    }

    .cart-right {
        background-color: #F4F6FB;
        border: 2px solid #E4EBFC;
        padding: 20px;
    }



    .cart-right input {
        box-shadow: none;
        background: #fff;

    }

    .cart-right .text-end1 {
        display: flex;
        align-items: end;
        justify-content: end;
    }

    .color-dark {
        color: #212529;
    }


    .order-summary h4 {
        padding-bottom: 10px;
        border-bottom: 1px solid #ccc;
    }

    .order-summary .row .col-md-8 p,
    .order-summary .row .col-md-4 p {
        font-weight: 500;
        letter-spacing: 0.5px;
        padding-bottom: 15px;
    }

    .secondary-color {
        /*color: #a558c8;*/
        color: #121286;
    }

    .order-row {
        border-bottom: 1px solid #ccc;
    }

    .taxes {
        font-size: 12px;
    }

    .dic-div {
        background-color: #D5EBE4;
        text-align: center;
        padding: 5px;
        font-weight: 500;
        letter-spacing: 0.5px;
        border-radius: 10px;
/*//1//*/
    }

    .left-icon {
        /*color: var(--primary) !important;*/
        color: #e97457 !important;
        margin-right: 15px;
        font-size: 20px;
    }

    .nav-pills .nav-link.active .left-icon {
        color: #ffffff !important;
    }

    .box-btn {
        background-color: #000;
    }

    .loose-btn {
        background-color: #000;
    }

    /* .w7{width:50px;}
    .w8{width:100px;}
    .w9{width:300px;}
    .w10{width:220px;}
    .w11{width:80px;}
    .w12{width:70px;}

    .w3-7{width:50px;}
    .w3-8{width:100px;}
    .w3-9{width:200px;}
    .w3-10{width:150px;}
    .w3-11{width:60px;}
    .w3-12{width:70px;}
    .w3-13{width:70px;} */

    .profile-arrow {
        color: #a558c8;
        text-align: right;
        width: 190px;
        position: absolute;
        font-size: 20px;
    }

    .orders-m .nav-link.active {
        color: #fff;
        /*background-color: #a558c8;*/
        background-color: #e97457;
        font-weight: 500;
        font-size: 15px;
        font-family: var(--secondary-font) !important;
        /* border-radius: 0px; */
        margin-left: 0px;
        border-radius: 18px 18px 0px 0px;
        -moz-border-radius: 18px 18px 0px 0px;
        -webkit-border-radius: 18px 18px 0px 0px;
        /*border: 0px solid #7d137d;*/
        border: 0px solid #e97457;
    }

    .orders-m .nav-link.active .left-icon {
        color: #fff !important;
    }

    .left-icon {
        /*color: var(--primary) !important;*/
        color: #e97457 !important;
        margin-right: 15px;
        font-size: 20px;
    }

    .nav-pills .nav-link.active .left-icon {
        color: #ffffff !important;
    }

    .box-btn {
        background-color: #000;
    }

    .loose-btn {
        background-color: #000;
    }

    th,
    td {
        font-size: 14px !important;
        font-weight: 600 !important;
        text-align: left;
        vertical-align: middle;
    }

    /* Align text in the second column (index 2) to the left */
    tr td:nth-child(2), tr th:nth-child(2)  {
        text-align: left;
    }

 

    .home-i{
        font-size:22px;
        background-color: #f0f0f0;
    padding: 15px;
    border-radius: 10px;
    }

    .home-i:hover{
        background-color:#121286;
        color: #f0f0f0;
    }

    .cart-l{
        background: #F4F6FB !important;
        height: fit-content !important;
        margin-top:15px !important;
        border: 2px solid #E4EBFC !important;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px !important;
    }

    .offer-tr{
        border-bottom: 1px solid #d8d8d8;
    }

    .cart-offer{
        background: #F4F6FB !important;
        height: fit-content !important;
        border-radius: 24px !important;
        margin-top:15px !important;
        /* border: 2px solid #E4EBFC !important; */
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px !important;
    }

    .order-management{
        font-family :"Inter", sans-serif !important;
        font-weight:900 !important;
    }

    .re-off{
        mix-blend-mode: darken;
    }

    .w-f{
        width:300px;
        padding: 15px;
    }

    .price-d{
        font-weight: 600;
    letter-spacing: 0.5px;
    }

    .diff{
        width: fit-content;
    color: green;
    font-weight: bold;
    }

    @media (max-width: 767px) {
        .col-sm-6{
            width: 50%;
        }

        .orders-m .nav-link.active {
            color: #fff;
            /*background-color: #a558c8;*/
            font-weight: 500;
            font-size: 15px;
            font-family: var(--secondary-font) !important;
            /* border-radius: 0px; */
            margin-left: 0px;
            border-radius: 0;
            -moz-border-radius: 0;
            -webkit-border-radius: 0;
            border: 0px solid #7d137d;
        }

        .orders-m .nav-link.active .left-icon {
            color: #fff !important;
        }
        
        

        .left-icon {
            /*color: var(--primary) !important;*/
            color: #e97457;
            margin-right: 15px;
            font-size: 20px;
        }

        .tabs1 , .tabs2 {
            position: relative;
            width: 100%;
        }

        .w-sm-100{
            width: 100%;
        }
        .heading {
            font-size: 25px;
            font-weight: 500 !important;
            text-transform: uppercase;
        }

        .dropdown-menu{
            width: 100%
        }
        .dropdown-menu li{
            width: 100%;
        }

    .ml-8 {
        margin-left: 0;
    }


    .w11 input[type='text'] {
        border-radius: 2px !important;
        border: 1px solid #979797 !important;
        box-sizing: border-box !important;
        padding: 1px !important;
        width: 100% !important;
    }
    }
    
    #v-pills-tab-mobile .nav-link {
  width: 50%;            /* each tab takes half */
  border-radius: 0;      /* square edges so they align nicely */
  margin-bottom: 2rem
}


</style>

<section class="order-management pt-56 pb-5">
    <div class="container-fluid ">
        <div class="row  d-none d-md-block mb-0">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="heading">
                    <span class="text-primary">Order</span>
                    <span class=""> Management</span>
                </h3>
                <a href="{{ route('homepage') }}" style="color:#e97457;">
                    <i class="fa fa-home home-i"></i>
                </a>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <!-- <div class="d-flex align-items-center flex-row justify-content-between">
                        <h3 class="heading">
                        <span class="text-primary">Order</span>
                        <span class="fw-600"> &nbsp;
                            Management</span>
                        </h3>

                    <a href="{{ route('homepage') }}">
                        <i class="fa fa-home" style="font-size:22px;" ></i>
                    </a>
                    </div> -->
                    <!-- <h3 class="heading">
                        <span class="text-primary">Order</span>
                        <span class="fw-600"> &nbsp;
                            Management</span>

                    </h3>
                    <i class="fa fa-home" aria-hidden="true"></i> -->
                    @php
                    // Initialize arrays to group products by reoffer status
                    $groupedProductsYes = []; // Products where reoffer is 'yes'
                    $groupedProductsNo = [];  // Products where reoffer is 'no'

                    // Group products based on the value of reoffer
                    foreach ($enquiriesForOfferList as $key => $offer_list) {
                        // Check if reoffer is 'yes'
                        if ($offer_list->reoffer == 'yes') {
                            // Calculate GST rate and selling price with GST
                            $gstRate = $offer_list->product->cgst + $offer_list->product->sgst;
                            $sellingPriceWithGst = $offer_list->offer_price * (1 + ($gstRate / 100));
                            
                             $mrp = $offer_list->mrp; // Store MRP in a variable
                            $discount = 0; // Initialize discount with a default value
                            
                            // Ensure MRP is greater than zero before calculating the discount
                            if ($mrp > 0) {
                                $discount = (($mrp - $sellingPriceWithGst) / $mrp) * 100;
                            }
                            
                            // Add discount to product data
                            $offer_list->discount = number_format($discount, 2);


                            // Add discount to product data
                            $offer_list->discount = number_format($discount, 2);

                            // Add to the 'yes' group
                            $groupedProductsYes[] = $offer_list;
                        } else {
                            // For 'no', simply add to the 'no' group
                            $groupedProductsNo[] = $offer_list;
                        }
                    }

                    // Count the number of products in each group
                    $yesCount = count($groupedProductsYes);
                    $noCount = count($groupedProductsNo);
                @endphp

                    <section class="py-5 header ml-8 d-none d-md-flex" style="width:100% !important;">
                        <!-- <div class=" py-4">
                                <div class="d-flex align-items-start">
                                    <div class="left-sidebar"  style="width:250px !important;"> -->

                        <!-- <div class="img-div">
                                            <img src="" class="empty-profile">
                                            <h4 class="companyname mt-4">ABC COMPANY</h4>
                                            <h5 class="mt-2">abc@gmail.com</h5>
                                        </div> -->

                        <!-- Tabs nav -->
                       <div class="nav nav-tabs orders-m nav-item" id="v-pills-tab" role="tablist">
                        <button class="nav-link tabs1" id="v-pills-enquiry-tab" data-bs-toggle="pill" data-bs-target="#v-pills-enquiry" type="button" role="tab" aria-controls="v-pills-enquiry" aria-selected="false"><i class="fa-solid fa-cart-shopping left-icon"></i> Enquiry <span class="countSpan">{{$quoteCounts}}</span></button>
                    
                        <div class="dropdown w-sm-100">
                            <button class="nav-link tabs2" id="v-pills-offer-dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-scroll left-icon"></i> Offer List<span class="countSpan">{{$offerListCount}}</span>
                                 <i class="fa-solid fa-caret-down ms-3" style="margin-top: -6px;"></i> 
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="v-pills-offer-dropdown-toggle" style="border-radius: 20px; padding: 0px;}">
                                <li>
                                    <button class="nav-link tabs1" id="v-pills-offer-tab" data-bs-toggle="pill" data-bs-target="#v-pills-offer" type="button" role="tab" aria-controls="v-pills-offer" aria-selected="false">New Offer ({{ ($noCount) }})</button>
                                </li>
                                <li>
                                    <button class="nav-link tabs1" id="v-pills-offer-tab2" data-bs-toggle="pill" data-bs-target="#v-pills-offer2" type="button" role="tab" aria-controls="v-pills-offer2" aria-selected="false">Reoffer ({{ ($yesCount) }})</button>
                                </li>
                            </ul>
                        </div>
                    
                        <button class="nav-link tabs1" id="v-pills-price-tab" data-bs-toggle="pill" data-bs-target="#v-pills-price" type="button" role="tab" aria-controls="v-pills-price" aria-selected="false"><i class="fa-solid fa-receipt left-icon"></i> My Price List <span class="countSpan">{{$totalPriceListCount}}</span></button>
                    
                        <button class="nav-link tabs1" id="v-pills-rejected-tab" data-bs-toggle="pill" data-bs-target="#v-pills-rejected" type="button" role="tab" aria-controls="v-pills-rejected" aria-selected="false"><i class="fa-solid fa-cart-plus left-icon"></i> Order cart <span class="countSpan">{{$cartsCount}}</span></button>
                    </div>

                </div>




<!-- Mobile Tabs Wrapper -->
<div class="d-md-none w-100" id="mobile-offer-tabs" style="display:none;">
  <div class="nav nav-tabs orders-m nav-item w-100" id="v-pills-tab-mobile" role="tablist">

    <!-- Offer Button -->
    <button class="nav-link tabs1" 
            id="v-pills-offer-tab-mobile" 
            data-bs-toggle="pill" 
            data-bs-target="#v-pills-offer" 
            type="button" role="tab" 
            aria-controls="v-pills-offer" 
            aria-selected="true">
        <i class="fa-solid fa-scroll left-icon"></i>
      New Offer <span class="countSpan">{{ $noCount }}</span>
    </button>

    <!-- Reoffer Button -->
    <button class="nav-link tabs1 flex-fill text-center" 
            id="v-pills-reoffer-tab-mobile" 
            data-bs-toggle="pill" 
            data-bs-target="#v-pills-offer2" 
            type="button" role="tab" 
            aria-controls="v-pills-offer2" 
            aria-selected="false">

              <i class="fa-solid fa-scroll left-icon"></i>
     Reoffer <span class="countSpan">{{ $yesCount }}</span>
    
    </button>

  </div>
</div>




                <div class="tab-content right-side1" id="v-pills-tabContent">
                    <div class="tab-pane fade" id="v-pills-orders" role="tabpanel" aria-labelledby="v-pills-orders-tab"
                        style="width:100% !important;">
                        <div class="orders-tab orders-tab-margin">
                            <h4>Requested <span class="color-primary"> Enquiry </span></h4>
                            <table class="table mt-2">
                                <tbody>
                                    <tr class="table-c">
                                        <td class="w1">Sr.No.</td>
                                        <td class="w2">Image</td>
                                        <td class="w3">Product Name</td>
                                        <td class="w4">Pattern</td>
                                        <td class="w5">Monthly Consumption</td>
                                        <td class="w6">Action</td>
                                    </tr>

                                    <tr>
                                        <td class="w1">01 </td>
                                        <td class="w2"><img src="assests/images/img.png" class="enquiry-img"></td>
                                        <td class="w3">Govind - Dahi, 1 Kg Pouch<br><span>Unit
                                                : 1 kg</span></td>
                                        <td class="w4"><select class="form-select" aria-label="Default select example">
                                                <option selected>Select</option>
                                                <option value="1">Loose Box</option>
                                                <option value="2">Cartoon box</option>
                                            </select></td>

                                        <td class="w5">
                                            <div class="quantity">
                                                <a href="#" class="quantity__minus"><span>-</span></a>
                                                <input name="quantity" type="text" class="quantity__input" value="1">
                                                <a href="#" class="quantity__plus"><span>+</span></a>
                                            </div>
                                            <input type="text form-control" class="optional ">
                                        </td>
                                        <td class="w6"><img src="assests/images/close.svg">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w1">02 </td>
                                        <td class="w2"><img src="assests/images/img.png" class="enquiry-img"></td>
                                        <td class="w3">Govind - Dahi, 1 Kg Pouch<br><span>Unit
                                                : 1 kg</span></td>
                                        <td class="w4">
                                            <select class="form-select" aria-label="Default select example">
                                                <option selected>Select</option>
                                                <option value="1">Loose Box</option>
                                                <option value="2">Cartoon box</option>
                                            </select></td>
                                        <td class="w5">
                                            <div class="quantity">
                                                <a href="#" class="quantity__minus"><span>-</span></a>
                                                <input name="quantity" type="text" class="quantity__input" value="1">
                                                <a href="#" class="quantity__plus"><span>+</span></a>
                                            </div>
                                            <input type="text form-control" class="optional ">
                                        </td>
                                        <td class="w6"><img src="assests/images/close.svg">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w1">03 </td>
                                        <td class="w2"><img src="assests/images/img.png" class="enquiry-img"></td>
                                        <td class="w3">Govind - Dahi, 1 Kg Pouch<br><span>Unit
                                                : 1 kg</span></td>
                                        <td class="w4"><select class="form-select" aria-label="Default select example">
                                                <option selected>Select</option>
                                                <option value="1">Loose Box</option>
                                                <option value="2">Cartoon box</option>
                                            </select></td>
                                        <td class="w5">
                                            <div class="quantity">
                                                <a href="#" class="quantity__minus"><span>-</span></a>
                                                <input name="quantity" type="text" class="quantity__input" value="1">
                                                <a href="#" class="quantity__plus"><span>+</span></a>
                                            </div>
                                            <input type="text form-control" class="optional ">
                                        </td>
                                        <td class="w6"><img src="assests/images/close.svg">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w1">04 </td>
                                        <td class="w2"><img src="assests/images/img.png" class="enquiry-img"></td>
                                        <td class="w3">Govind - Dahi, 1 Kg Pouch<br><span>Unit
                                                : 1 kg</span></td>
                                        <td class="w4"><select class="form-select" aria-label="Default select example">
                                                <option selected>Select</option>
                                                <option value="1">Loose Box</option>
                                                <option value="2">Cartoon box</option>
                                            </select></td>
                                        <td class="w5">
                                            <div class="quantity">
                                                <a href="#" class="quantity__minus"><span>-</span></a>
                                                <input name="quantity" type="text" class="quantity__input" value="1">
                                                <a href="#" class="quantity__plus"><span>+</span></a>
                                            </div>
                                            <input type="text form-control" class="optional ">
                                        </td>
                                        <td class="w6"><img src="assests/images/close.svg">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w1">05 </td>
                                        <td class="w2"><img src="assests/images/img.png" class="enquiry-img"></td>
                                        <td class="w3">Govind - Dahi, 1 Kg Pouch<br><span>Unit
                                                : 1 kg</span></td>
                                        <td class="w4"><select class="form-select" aria-label="Default select example">
                                                <option selected>Select</option>
                                                <option value="1">Loose Box</option>
                                                <option value="2">Cartoon box</option>
                                            </select></td>
                                        <td class="w5">
                                            <div class="quantity">
                                                <a href="#" class="quantity__minus"><span>-</span></a>
                                                <input name="quantity" type="text" class="quantity__input" value="1">
                                                <a href="#" class="quantity__plus"><span>+</span></a>
                                            </div>
                                            <input type="text form-control" class="optional ">
                                        </td>
                                        <td class="w6"><img src="assests/images/close.svg">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w1">06 </td>
                                        <td class="w2"><img src="assests/images/img.png" class="enquiry-img"></td>
                                        <td class="w3">Govind - Dahi, 1 Kg Pouch<br><span>Unit
                                                : 1 kg</span></td>
                                        <td class="w4"><select class="form-select" aria-label="Default select example">
                                                <option selected>Select</option>
                                                <option value="1">Loose Box</option>
                                                <option value="2">Cartoon box</option>
                                            </select></td>
                                        <td class="w5">
                                            <div class="quantity">
                                                <a href="#" class="quantity__minus"><span>-</span></a>
                                                <input name="quantity" type="text" class="quantity__input" value="1">
                                                <a href="#" class="quantity__plus"><span>+</span></a>
                                            </div>
                                            <input type="text form-control" class="optional ">
                                        </td>
                                        <td class="w6"><img src="assests/images/close.svg">
                                        </td>
                                    </tr>



                                </tbody>
                            </table>
                        </div>



                    </div>

                     <div class="tab-pane fade" id="v-pills-enquiry" role="tabpanel"
                        aria-labelledby="v-pills-enquiry-tab">
                    <div class="orders-tab orders-tab-margin">
                        <div id="enquiryCart">
                            <h4 class="mb-4">Enquiry <span class="color-primary">Cart</span> </h4>
                            </div>
                            @if (isset($quote_Items_list) && $quote_Items_list->count() > 0)
                                <form id="enquiryForm">
                                    @csrf

                                    @php
                                      
                                                    $counter = 1; // Initialize counter for each category
                                                @endphp
                                   
                                        <div class="d-none d-md-block">
                                        <div class="table-responsive">
                                            <table class="table "> <!-- Hide on small screens -->
                                                <!-- Table header -->
                                                <thead>
                                                <tr class="table-c">
                                                    <th class="w7">Sr.No.</th>
                                                    <th class="w8">Image</th>
                                                    <th class="w9">Product Name</th>
                                                    <th class="w10">Order Quantity Type</th>
                                                    <th class="w11">Monthly Consumption</th>
                                                    <th class="w12">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                             
                                                @foreach($quote_Items_list as $key => $quote_Items)
                                                    <tr>
                                                        <td class="w7">{{ $counter }}</td>
                                                        <td class="w8">
                                                             @if($quote_Items->product && $quote_Items->product->image)
        <img src="uploads/{{ $quote_Items->product->image }}" class="enquiry-img">
    @else
        <span>No Image Available</span>
    @endif
                                                        </td>
                                                        <td class="w9">{{ $quote_Items->product->product_name }}</td>
                                                        <td class="w10">
                                                            @if ($quote_Items->product_type == '1')
                                                                <span style="color:red">Carton Box : {{ $quote_Items->product->carton_size }}.</span>
                                                                <input type="hidden" name="discount{{ $counter }}" id="discount{{ $counter }}" value="{{ $quote_Items->product->carton_discount_basic }}">
                                                                <input type="hidden" name="offer_price{{ $counter }}" id="offer_price{{ $counter }}" value="{{ $quote_Items->product->sale_price_carton }}">
                                                                <input type="hidden" class="form-select" name="product_types{{ $counter }}" value="{{ $quote_Items->product_type }}">
                                                                <input type="hidden" class="form-select" name="quantity{{ $counter }}" value="{{ $quote_Items->quantity }}">
                                                            @elseif ($quote_Items->product_type == '2')
                                                                <span style="color:red">Loose (pcs.)</span>
                                                                <input type="hidden" name="discount{{ $counter }}" id="discount{{ $counter }}" value="{{ $quote_Items->product->loose_discount_basic }}">
                                                                <input type="hidden" name="offer_price{{ $counter }}" id="offer_price{{ $counter }}" value="{{ $quote_Items->product->sale_price_loose_pcs }}">
                                                                <input type="hidden" class="form-select" name="quantity{{ $counter }}" value="{{ $quote_Items->quantity }}">
                                                                <input type="hidden" class="form-select" name="product_types{{ $counter }}" value="{{ $quote_Items->product_type }}">
                                                            @else
                                                                <span style="color:#00f">Loose/Box Not</span>
                                                            @endif
                                                            <input type="hidden" name="product_id{{ $counter }}" value="{{ $quote_Items->product->id }}">
                                                        </td>
                                                        <td class="w11">
                                                            <input type="text" maxlength="3"  name="monthlyconsumption{{ $counter }}" id="monthlyconsumption{{ $counter }}" value="">
                                                            <input type="hidden" name="mrp{{ $counter }}" id="mrp{{ $counter }}" value="{{ $quote_Items->product->product_mrp }}">
                                                        </td>
                                                        <td class="w12">
                                                            <a href="{{ route('removequote', $quote_Items->id) }}">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @php $counter++; @endphp
                                                @endforeach
                                            </tbody>
                                            </table>
                                        </div>
                                        </div>

                                        <!-- Grid layout for small screens -->
                                      <div class="row d-md-none">

    @php $mobileCounter = 1; @endphp
    @foreach($quote_Items_list as $key => $quote_Items)

        <div class="col-12 mb-3">
            <div class="row mobilecard-320 p-2">

                {{-- IMAGE --}}
                <div class="col-4 d-flex align-items-start">
                    <img src="uploads/{{ $quote_Items->product->image }}"
                         class="newpriceimage1 mobile-image-320"
                         alt="Product Image">
                </div>

                {{-- DETAILS --}}
                <div class="col-8 mobile-details-320">

                    <h5 class="card-title mb-1">{{ $quote_Items->product->product_name }}</h5>

                    <p class="card-text mb-2">
                        @if ($quote_Items->product_type == '1')
                            Carton Box : 24 Nos.
                        @elseif ($quote_Items->product_type == '2')
                            Loose (pcs.)
                        @else
                            Loose/Box Not
                        @endif
                    </p>

                    <div class="d-flex align-items-center gap-2 mobile-action-row-320">
                        <input type="text"
                               class="mobile-input-320"
                               maxlength="3"
                               placeholder="Monthly Consumption"
                               name="monthlyconsumption{{ $mobileCounter }}"
                               id="monthlyconsumption{{ $mobileCounter }}"
                               style="font-size: 7px;" maxlength="3"  
                               >

                        <input type="hidden"
                               name="mrp{{ $mobileCounter }}"
                               id="mrp{{ $mobileCounter }}"
                               value="{{ $quote_Items->product->product_mrp }}">

                        <a href="{{ route('removequote', $quote_Items->id) }}"
                           class="btn btn-theme mobile-remove-btn-320">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>

                </div>

            </div>
        </div>

        @php $mobileCounter++; @endphp
    @endforeach

</div>
                  

                                        <a id="submitEnquiryButton" style="position: relative; bottom: 0; top: 20px; cursor: pointer;" class="add-button addcart-button buy-button text-light red-btn submitEnquiry">
                                            Request to quote
                                        </a>
                                            </form>
                                @else
                                    <div style="margin-top: 30px; background: aliceblue; padding: 10px; font-weight: 500; font-size: 18px; letter-spacing: 0.5px;">
                                        <span>Add new product to your enquiry cart to get offer.</span>
                                    </div>
                             @endif



                        </div>

                   
                     </div>
                     
                 <div class="tab-pane fade" id="v-pills-offer" role="tabpanel" aria-labelledby="v-pills-offer-tab">
                      <div class="orders-tab orders-tab-margin">
                    <h4>New <span class="color-primary">Offer List</span></h4>
                    <div class="row margin-t">
<!--                       @php-->
<!--    // Initialize an associative array to store products grouped by category name-->
<!--    $groupedProducts = [];-->

<!--    // Group products by category name-->
<!--    foreach ($enquiriesForOfferList as $key => $offer_list) {-->
<!--        // Check if reoffer is 'no'-->
<!--        if ($offer_list->reoffer == 'no') {-->
<!--            // Calculate GST rate and selling price with GST-->
<!--            $gstRate = $offer_list->product->cgst + $offer_list->product->sgst;-->
<!--            $sellingPriceWithGst = $offer_list->offer_price * (1 + ($gstRate / 100));-->

<!--            // Check if MRP is greater than zero before calculating the discount-->
<!--            if ($offer_list->mrp > 0) {-->
<!--                // Calculate discount-->
<!--                $discount = (($offer_list->mrp - $sellingPriceWithGst) / $offer_list->mrp) * 100;-->
<!--                // Add discount to product data-->
<!--                $offer_list->discount = number_format($discount, 2);-->
<!--            } else {-->
<!--                // If MRP is zero, set the discount to zero or handle accordingly-->
<!--                $offer_list->discount = 0;-->
<!--            }-->

<!--            // Add to grouped products-->
<!--            $groupedProducts[] = $offer_list;-->
<!--        }-->
<!--    }-->
<!--@endphp-->


@php
$groupedProducts = [];

foreach ($enquiriesForOfferList as $key => $offer_list) {

   
    if ($offer_list->reoffer != 'no') {
        continue;
    }

  
    if (!$offer_list->product) {
        continue;   // or log this if needed
    }

    $cgst = $offer_list->product->cgst ?? 0;
    $sgst = $offer_list->product->sgst ?? 0;

    $gstRate = $cgst + $sgst;
    $sellingPriceWithGst = $offer_list->offer_price * (1 + ($gstRate / 100));

    if ($offer_list->mrp > 0) {
        $discount = (($offer_list->mrp - $sellingPriceWithGst) / $offer_list->mrp) * 100;
        $offer_list->discount = number_format($discount, 2);
    } else {
        $offer_list->discount = 0;
    }

    $groupedProducts[] = $offer_list;
}
@endphp


                
                        @if (count($groupedProducts) > 0)
                            <div class="section mt-2">
                                <hr class="brk-line">
                            </div>
                
                            <div class="d-none d-md-block">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr class="table-c">
                                                <th></th>
                                                <th class="w3-7">Sr.No.</th>
                                                <th class="w3-8">Image</th>
                                                <th class="w3-9">Product Name</th>
                                                <th class="w3-10">Order Quantity Type</th>
                                                <th class="w3-11">Offer Price (Basic)</th>
                                                <th class="w3-12">MRP</th>
                                                <th class="w3-12">Discount</th>
                                                <!-- <th class="w3-12">Counter Price/Comment</th> -->
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($groupedProducts as $key => $offer_list)
                                                <tr>
                                                    <td>
                                                        @if ($offer_list->alert === 'active')
                                                            <i class="fa fa-info-circle text-danger" aria-hidden="true" title="Price changed."></i>
                                                        @endif
                                                    </td>
                                                    <td class="w3-7">{{ $key + 1 }}</td>
                                                    <td class="w3-8">
                                                        <img src="uploads/{{ $offer_list->product->image }}" class="enquiry-img" alt="Product Image">
                                                    </td>
                                                    <td class="w3-9 w-f">
                                                        <div class="d-flex flex-column">
                                                            <span>{{ $offer_list->product->product_name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="w3-10">
                                                        <div class="d-flex flex-column">
                                                            @if ($offer_list->product_types == 1)
                                                                <span style="color:red">Carton Box : {{ $offer_list->product->carton_size }}.</span>
                                                            @elseif ($offer_list->product_types == 2)
                                                                <span style="color: red">Loose (pcs.)</span>
                                                            @else
                                                                <span style="color: blue">Loose/Box Not</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="w3-11">
                                                        <div class="d-flex flex-column">
                                                            @if ($offer_list->offer_price)
                                                                ₹ {{ $offer_list->offer_price }}
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="w3-12">
                                                        <div class="d-flex flex-column">
                                                            @if ($offer_list->mrp)
                                                                ₹ {{ $offer_list->mrp }}
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="w3-12">
                                                        <div class="d-flex flex-column">
                                                            {{ $offer_list->discount }} %
                                                        </div>
                                                    </td>
                                                    <!-- <td class="w3-12">
                                                        <div class="d-flex flex-column">
                                                            {{ $offer_list->expected_price_value ? $offer_list->expected_price_value : 'No Counter/Comment' }}
                                                        </div>
                                                    </td> -->
                
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <div class="d-flex">
                                                                <form class="offerRequestForm offerForm" id="offerRequestForm_{{ $offer_list->id }}" action="{{ route('offer.request', $offer_list->id) }}" method="POST" class="re-off">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="accept">
                                                                    <input type="hidden" name="updated_at" value="{{ $offer_list->updated_at }}">
                                                                    <input type="hidden" name="offerRequestFormid" value="{{ $offer_list->id }}">
                                                                    <button type="button" class="tick acceptButton" title="Accept"><i class="fa-solid fa-check"></i></button>
                                                                </form>
                
                                                                <form action="{{ route('offer.reject', $offer_list->id) }}" method="POST" rel="noopener noreferrer" class="re-off offerRejectForm">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="rejected">
                                                                    <button type="submit" class="rejectButton cross" title="Rejected"><i class="fa-solid fa-xmark"></i></button>
                                                                </form>
                                                                 @if($offer_list->reoffer_count < 3)
                                                              <button 
                                                                      type="button"
                                                                      class="money"
                                                                      data-bs-toggle="modal"
                                                                      data-bs-target="#exampleModal_{{ $offer_list->id }}"
                                                                      data-offer-id="{{ $offer_list->id }}"
                                                                      title="Reoffer">
                                                                      <i class="fa-solid fa-money-check"></i>
                                                                    </button>
                                                                  @endif
                                                            </div>
                
                                                              <div class="modal fade" id="exampleModal_{{ $offer_list->id }}" tabindex="-1">
                                                                <div class="modal-dialog modal-dialog-centered modal-w">
                                                                    <div class="modal-content" style="width:22rem;">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form action="{{ route('offer.reoffer', $offer_list->id) }}" method="POST" rel="noopener noreferrer" class="offerReofferForm">
                                                                                @csrf
                                                                                <label class="mb-2 modal-h">Enter Counter Price/Comment</label>
                                                                                <input type="text" class="form-control" name="expected_price_value" required>
                                                                                <button type="submit" class="reofferButton btn red-btn mb-3 add-button addcart-button btn buy-button text-light red-btn1 mt-2">Reoffer</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                
                            <div class="d-md-none">
                                <div class="row">
                                    @foreach ($groupedProducts as $key => $offer_list)
                                        <div class="col-12">
                                            <div class="card mb-3">
                                                <div class="row g-0">
                                                    <div class="col-sm-4">
                                                        <img src="uploads/{{ $offer_list->product->image }}" class="card-img-top newpriceimage" alt="Product Image">
                                                    </div>
                                                    <div class="col-sm-8 p-2">
                                                        <div class="card-body">
                                                            <h5 class="card-title" style="font-weight: 700"><strong>{{ $offer_list->product->product_name }}</strong></h5>
                                                            <p class="card-text mb-3">
                                                            @if ($offer_list->product_types == 1)
                                                                <span style="color:red">Carton Box : {{ $offer_list->product->carton_size }}.</span>
                                                            @elseif ($offer_list->product_types == 2)
                                                                <span style="color: red">Loose (pcs.)</span>
                                                            @else
                                                                <span style="color: blue">Loose/Box Not</span>
                                                            @endif
                                                            </p>
                                                            <p class="card-text my-2"><strong>Offer Price:</strong> 
                                                                @if ($offer_list->offer_price)
                                                                    ₹ {{ $offer_list->offer_price }}
                                                                @endif
                                                            </p>
                                                            <p class="card-text my-2"><strong>MRP:</strong> 
                                                                @if ($offer_list->mrp)
                                                                    ₹ {{ $offer_list->mrp }}
                                                                @endif
                                                            </p>
                                                            <p class="card-text my-2"><strong>Discount:</strong> 
                                                                @if ($offer_list->discount)
                                                                    {{ $offer_list->discount }} %
                                                                @endif
                                                            </p>
                                                            <!--<p class="card-text my-2"><strong>Counter Price/Comment:</strong> -->
                                                            <!--    @if ($offer_list->expected_price_value)-->
                                                            <!--        {{ $offer_list->expected_price_value }}-->
                                                            <!--    @else-->
                                                            <!--        0-->
                                                            <!--    @endif-->
                                                            <!--</p>-->
                                                            <p>
                                                                <div class="d-flex flex-column">
                                                                    <div class="d-flex">
                                                                        <form class="offerRequestForm offerForm" id="offerRequestForm_{{ $offer_list->id }}" action="{{ route('offer.request', $offer_list->id) }}" method="POST" class="re-off">
                                                                            @csrf
                                                                            <input type="hidden" name="status" value="accept">
                                                                            <input type="hidden" name="updated_at" value="{{ $offer_list->updated_at }}">
                                                                            <input type="hidden" name="offerRequestFormid" value="{{ $offer_list->id }}">
                                                                            <button type="button" class="tick acceptButton" title="Accept"><i class="fa-solid fa-check"></i></button>
                                                                        </form>
                
                                                                        <form action="{{ route('offer.reject', $offer_list->id) }}" method="POST" rel="noopener noreferrer" class="re-off offerRejectForm">
                                                                            @csrf
                                                                            <input type="hidden" name="status" value="rejected">
                                                                            <button type="submit" class="rejectButton cross" title="Rejected"><i class="fa-solid fa-xmark"></i></button>
                                                                        </form>
                                                                         @if ($offer_list->reoffer_count < 3)
                                                                     <button type="button"
                                                                          class="money"
                                                                          data-id="{{ $offer_list->id }}"
                                                                          data-bs-toggle="modal"
                                                                          data-bs-target="#modal_{{ $offer_list->id }}"
                                                                            title="Reoffer">
                                                                            <i class="fa-solid fa-money-check"></i>
                                                                        </button>
                                                                        @endif
                                                                    </div>
                
                                                                    
                                                                </div>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                           @foreach ($groupedProducts as $offer_list)
<div class="modal fade" id="modal_{{ $offer_list->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Reoffer — {{ $offer_list->product->product_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                 <form action="{{ route('offer.reoffer', $offer_list->id) }}" method="POST" rel="noopener noreferrer" class="offerReofferForm">
                    @csrf

                    <label class="mb-2">Enter Counter Price/Comment</label>
                    <input type="text" class="form-control" name="expected_price_value" required>

                    <button type="submit" class="reofferButton btn red-btn mb-3 add-button addcart-button btn buy-button text-light red-btn1 mt-2">Submit Reoffer</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endforeach


                        @else
                            <div style="margin-top: 30px; background: aliceblue; padding: 10px; font-weight: 500; font-size: 18px; letter-spacing: 0.5px;">
                                <span>No new offer available from enquiry please contact to customer care if enquiry submitted.</span>
                            </div>
                        @endif
                    </div>
                </div>




            </div>


                    <div class="tab-pane fade" id="v-pills-offer2" role="tabpanel" aria-labelledby="v-pills-offer-tab2">
                        <div class="orders-tab orders-tab-margin">
                            @php
                                // Initialize an associative array to store products grouped by category name
                                $groupedProducts = [];
                            
                                // Group products by category name
                                foreach ($enquiriesForOfferList as $key => $offer_list) {
                                    // Check if reoffer is 'yes'
                                    if ($offer_list->reoffer == 'yes') {
                                        // Calculate GST rate and selling price with GST
                                        $gstRate = $offer_list->product->cgst + $offer_list->product->sgst;
                                        $sellingPriceWithGst = $offer_list->offer_price * (1 + ($gstRate / 100));
                                        
                                        $mrp = $offer_list->mrp; // Store MRP in a variable
                            $discount = 0; // Initialize discount with a default value
                            
                            // Ensure MRP is greater than zero before calculating the discount
                            if ($mrp > 0) {
                                $discount = (($mrp - $sellingPriceWithGst) / $mrp) * 100;
                            }
                        
                                        // Add discount to product data
                                        $offer_list->discount = number_format($discount, 2);
                        
                                        $groupedProducts[] = $offer_list;
                                    }
                                }
                            @endphp
                            
                            <h4>My <span class="color-primary">Reoffer List</span></h4>
                        
                            <div class="row margin-t">
                                @if (count($groupedProducts) > 0)
                                <div class="section mt-2">
                                    <hr class="brk-line">
                                </div>
                                <div class="d-none d-md-block">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr class="table-c">
                                                    <th></th>
                                                    <th class="w3-7">Sr.No.</th>
                                                    <th class="w3-8">Image</th>
                                                    <th class="w3-9">Product Name</th>
                                                    <th class="w3-10">Order Quantity Type</th>
                                                    <th class="w3-11">Offer Price (Basic)</th>
                                                    <th class="w3-12">MRP</th>
                                                    <th class="w3-12">Discount</th>
                                                    <!-- <th class="w3-12">Counter Price/Comment</th> -->
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($groupedProducts as $key => $offer_list)
                                                <tr>
                                                    <td>@if ($offer_list->alert === 'active')
                                                        <i class="fa fa-info-circle text-danger" aria-hidden="true"
                                                        title="Price changed."></i>
                                                        @endif
                                                    </td>
                                                    <td class="w3-7">{{ $key + 1 }}</td>
                                                    <td class="w3-8"><img src="uploads/{{ $offer_list->product->image }}" class="enquiry-img"></td>
                                                    <td class="w3-9 w-f">
                                                        <div class="d-flex flex-column">
                                                            <span>{{ $offer_list->product->product_name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="w3-10">
                                                        <div class="d-flex flex-column">
                                                            @if ($offer_list->product_types == 1)
                                                            <span style="color:red">Carton Box : {{ $offer_list->product->carton_size }}.</span>
                                                            @elseif ($offer_list->product_types == 2)
                                                            <span style="color: red">Loose (pcs.)</span>
                                                            @else
                                                            <span style="color: blue">Loose/Box Not</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="w3-11">
                                                        <div class="d-flex flex-column">
                                                            @if ($offer_list->offer_price)
                                                            ₹ {{ $offer_list->offer_price }}
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="w3-12">
                                                        <div class="d-flex flex-column">
                                                            @if ($offer_list->mrp)
                                                            ₹ {{ $offer_list->mrp }}
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="w3-12">
                                                        <div class="d-flex flex-column">
                                                            {{ $offer_list->discount }} %
                                                        </div>
                                                    </td>
                                                    <!-- <td class="w3-12">
                                                        <div class="d-flex flex-column">
                                                            @if ($offer_list->expected_price_value)
                                                            {{ $offer_list->expected_price_value }}
                                                            @endif
                                                        </div>
                                                    </td> -->
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <div class="d-flex">
                                                <form class="offerRequestForm reofferForm" id="offerRequestForm_{{ $offer_list->id }}" action="{{ route('offer.request', $offer_list->id) }}" method="POST" class="re-off">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="accept">
                                                                    <input type="hidden" name="updated_at" value="{{ $offer_list->updated_at }}">
                                                                    <input type="hidden" name="offerRequestFormid" value="{{ $offer_list->id }}">
                                                                    <button type="button" class="tick acceptButton" title="Accept"><i class="fa-solid fa-check"></i></button>
                                                                </form>
                        
                                                                <form action="{{ route('offer.reject', $offer_list->id) }}" method="POST"
                                                                    rel="noopener noreferrer" class="re-off reofferRejectForm">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="rejected">
                                                                    <button type="submit" class="rejectButton cross" title="Rejected"><i class="fa-solid fa-xmark"></i></button>
                                                                </form>
                                                                 @if($offer_list->reoffer_count < 3)
                                                                <!--<button type="submit" class="money" data-bs-toggle="modal" data-bs-target="#reofferModal" title="Reoffer">-->
                                                                <!--    <i class="fa-solid fa-money-check"></i>-->
                                                                <!--</button>-->
                                                                
                                                                <button 
                                                                      type="button"
                                                                      class="money"
                                                                      data-bs-toggle="modal"
                                                                      data-bs-target="#exampleModal_{{ $offer_list->id }}"
                                                                      data-offer-id="{{ $offer_list->id }}"
                                                                      title="Reoffer">
                                                                      <i class="fa-solid fa-money-check"></i>
                                                                    </button>
                                                                                                                                    
                                                              @endif
                                                            </div>
                        
                                                            <!--<div class="modal fade" id="reofferModal" tabindex="-1"-->
                                                            <!--aria-labelledby="exampleModalLabel" aria-hidden="true">-->
                                                            
                                                              <div class="modal fade" id="exampleModal_{{ $offer_list->id }}" tabindex="-1">
                                                                <div class="modal-dialog modal-dialog-centered modal-w">
                                                                    <div class="modal-content" style="width:22rem;">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form action="{{ route('offer.reoffer', $offer_list->id) }}"
                                                                                method="POST" rel="noopener noreferrer" class="reofferReofferForm">
                                                                                @csrf
                                                                                <label class="mb-2 modal-h">Enter Counter Price/Comment</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="expected_price_value" value="" required>
                                                                                <button type="submit"
                                                                                    class="reofferButton btn red-btn mb-3 add-button addcart-button btn buy-button text-light red-btn1 mt-2">Reoffer</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-md-none">
                                    <div class="row">
                                        @foreach ($groupedProducts as $key => $offer_list)
                                            <div class="col-12">
                                                <div class="card mb-3">
                                                    <div class="row g-0">
                                                        <div class="col-sm-4">
                                                            <img src="uploads/{{ $offer_list->product->image }}" class="card-img-top newpriceimage" alt="Product Image">
                                                        </div>
                                                        <div class="col-sm-8 p-2">
                                                            <div class="card-body">
                                                                <h5 class="card-title" style="font-weight: 700"><strong>{{ $offer_list->product->product_name }}</strong></h5>
                                                                <p class="card-text mb-3">
                                                                @if ($offer_list->product_types == 1)
                                                            <span style="color:red">Carton Box : {{ $offer_list->product->carton_size }}.</span>
                                                            @elseif ($offer_list->product_types == 2)
                                                            <span style="color: red">Loose (pcs.)</span>
                                                            @else
                                                            <span style="color: blue">Loose/Box Not</span>
                                                            @endif
                                                                </p>
                                                                <p class="card-text my-2"><strong>Offer Price:</strong> @if ($offer_list->offer_price)
                                                                ₹ {{ $offer_list->offer_price }}
                                                                @endif</p>
                                                                <p class="card-text my-2"><strong>MRP:</strong> @if ($offer_list->mrp)
                                                                ₹ {{ $offer_list->mrp }}
                                                                @endif</p>
                                                                <p class="card-text my-2"><strong>Discount:</strong> @if ($offer_list->discount)
                                                                {{ $offer_list->discount }} %
                                                                @endif</p>
                                                                <!--<p class="card-text my-2"><strong>Counter Price/Comment:</strong> @if ($offer_list->expected_price_value)-->
                                                                <!--₹ {{ $offer_list->expected_price_value }} @else-->
                                                                <!--₹ 0-->
                                                                <!--@endif</p>-->
                                                                <p>
                                                                    <div class="d-flex flex-column">
                                                                        <div class="d-flex">
                                                                            <form class="offerRequestForm reofferForm" id="offerRequestForm_{{ $offer_list->id }}" action="{{ route('offer.request', $offer_list->id) }}" method="POST" class="re-off">
                                                                                @csrf
                                                                                <input type="hidden" name="status" value="accept">
                                                                                <input type="hidden" name="updated_at" value="{{ $offer_list->updated_at }}">
                                                                                <input type="hidden" name="offerRequestFormid" value="{{ $offer_list->id }}">
                                                                                <button type="button" class="tick acceptButton" title="Accept"><i class="fa-solid fa-check"></i></button>
                                                                            </form>
                        
                                                                            <form action="{{ route('offer.reject', $offer_list->id) }}" method="POST"
                                                                                rel="noopener noreferrer" class="re-off reofferRejectForm">
                                                                                @csrf
                                                                                <input type="hidden" name="status" value="rejected">
                                                                                <button type="submit" class="rejectButton  cross" title="Rejected"><i class="fa-solid fa-xmark"></i></button>
                                                                            </form>
                                                                              @if ($offer_list->reoffer_count < 3)
                    <button type="button"
                                  class="money"
                                  data-id="{{ $offer_list->id }}"
                                  data-bs-toggle="modal"
                                  data-bs-target="#modal_{{ $offer_list->id }}"
                                    title="Reoffer">
                                    <i class="fa-solid fa-money-check"></i>
                                </button>
                @endif
                                                                        </div>
                        
                                                                      
                                                                    </div>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
@foreach ($groupedProducts as $offer_list)
<div class="modal fade" id="modal_{{ $offer_list->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Reoffer: {{ $offer_list->product->product_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('offer.reoffer', $offer_list->id) }}" method="POST" class="reofferReofferForm">
                    @csrf
                    <label class="mb-2">Enter Counter Price / Comment</label>
                    <input type="text" class="form-control" name="expected_price_value" required>

                    <button type="submit"
                        class="reofferButton btn red-btn mb-3 add-button addcart-button btn buy-button text-light red-btn1 mt-2">Reoffer</button>


                    <!-- <button class="btn btn-primary mt-3 w-100">Submit Reoffer</button> -->
                </form>
            </div>

        </div>
    </div>
</div>
@endforeach

                                @else
                                    <div style="margin-top: 30px; background: aliceblue; padding: 10px; font-weight: 500; font-size: 18px; letter-spacing: 0.5px;">
                                        <span>Dear customer, stay tuned you will get revised offer shortly.</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                   
              <div class="tab-pane fade" id="v-pills-price" role="tabpanel" aria-labelledby="v-pills-price-tab">
    <div class="orders-tab orders-tab-margin">
        <div id="priceCart">
            <h4>My <span class="color-primary"> Price List</span></h4>
        </div>

        <div class="row margin-t">
            <!-- Search (Desktop only) -->
           <div class="col-md-12 mb-3">
  <div class="d-flex flex-column flex-md-row justify-content-md-end align-items-md-center">
    <input type="text"
           id="searchInput"
           class="form-control w-100 w-md-25 mt-2 mt-md-0"
           placeholder="Search Product"
           oninput="filterTable()">
  </div>
</div>


         
            <!-- Desktop Table -->
            <div class="d-none d-md-block">
                <div class="table-responsive">
                    <table class="table" id="priceTable">
                        <thead>
                            <tr class="table-c">
                                <td class="w3-7">Sr.No.</td>
                                <td class="w3-8">Image</td>
                                <td class="w3-9">Product Name</td>
                                <td class="w3-10">Order Quantity Type</td>
                                <td class="w3-12">MRP</td>
                                <td class="w3-11">Approved Price (Basic)</td>
                                <td class="w3-12">Discount</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enquiriesForAccept as $key => $acceptLits)
                            @php
                                $gstRate = $acceptLits->product->cgst + $acceptLits->product->sgst;
                                $sellingPriceWithGst = $acceptLits->offer_price * (1 + ($gstRate / 100));
                                $discount = $sellingPriceWithGst > $acceptLits->mrp ? 0 : (($acceptLits->mrp - $sellingPriceWithGst) / $acceptLits->mrp) * 100;
                                $A = $acceptLits->product->cost_per_item - $acceptLits->cost_per_item;
                            @endphp
                            
                            @php
                                $needsPriceConfirmation = false;
                                $priceType = null;
                                $oldCost = $acceptLits->offer_price;
                                $newCost = null;
                            
                                // CASE 2 — customer price (only if NOT already accepted)
                                if (
                                    $acceptLits->price_source !== 'customer' &&
                                    isset($customerPrices[$acceptLits->product_id]) &&
                                    $acceptLits->offer_price != $customerPrices[$acceptLits->product_id]->product_price
                                ) {
                                    $needsPriceConfirmation = true;
                                    $priceType = 'customer';
                                    $newCost = $customerPrices[$acceptLits->product_id]->product_price;
                                }
                            
                                // CASE 1 — product price (ONLY if customer price not locked)
                                elseif (
                                    $acceptLits->price_source !== 'customer' &&
                                    $acceptLits->product &&
                                    $acceptLits->cost_per_item != $acceptLits->product->cost_per_item
                                ) {
                                    $needsPriceConfirmation = true;
                                    $priceType = 'product';
                                    $newCost = $acceptLits->product->cost_per_item;
                                }
                            @endphp
                             <tr>
                                <td class="w3-7">{{ $key + 1 }}</td>
                                <td class="w3-8"><img src="uploads/{{ $acceptLits->product->image }}" class="enquiry-img"></td>
                                <td class="w3-9 w-f">{{ $acceptLits->product->product_name }}</td>
                                <td>
                                    @if ($acceptLits->product_types == 1)
                                        <span style="color:red">Carton Box :{{ $acceptLits->product->carton_size ?? 0 }}</span>
                                    @elseif ($acceptLits->product_types == 2)
                                        <span style="color: red">Loose (pcs.)</span>
                                    @else
                                        <span style="color: blue">Loose/Box Not</span>
                                    @endif
                                </td>
                                <td>₹ {{ $acceptLits->mrp }}</td>
                                <td>₹ {{ $acceptLits->offer_price }}</td>
                                <td>{{ number_format($discount, 2) }} %</td>
                                <td class="w3-10">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex">
                                            @if ($acceptLits->product->status == 'inactive')
                                                <span style="color: red;">This product is unavailable</span>
                                          @elseif ($needsPriceConfirmation)
                                            <i class="fa fa-info-circle"
                                               style="font-size:41px;color:red;cursor:pointer;"
                                               title="Click to confirm updated price"
                                               onclick="openPriceConfirmModal(
                                                    {{ $acceptLits->id }},
                                                    '{{ $priceType }}',
                                                    {{ $oldCost }},
                                                    {{ $newCost }},
                                                    '{{ addslashes($acceptLits->product->product_name) }}'
                                               )">
                                            </i>
                                            @else
                                                <form action="{{ route('cart.create') }}" method="POST" class="add-to-cart-form">
                                                    @csrf
                                                    <input type="hidden" name="product_types" value="{{ $acceptLits->product_types }}">
                                                    <input type="hidden" name="enquiry_id" value="{{ $acceptLits->id }}">
                                                    <input type="hidden" name="product_id" value="{{ $acceptLits->product_id }}">
                                                    <input type="hidden" name="quantity" value="{{ $acceptLits->product_types == 1 ? $acceptLits->product->carton_size : 1 }}">
                                                    <input type="hidden" name="offer_price" value="{{ $acceptLits->offer_price }}">
                                                    <input type="hidden" name="mrp" value="{{ $acceptLits->mrp }}">
                                                    <input type="hidden" name="discount" value="{{ number_format($discount, 2) }}">
                                                    <input type="hidden" name="expected_price_value" value="{{ $acceptLits->expected_price_value }}">
                                                    <input type="hidden" name="monthlyconsumption" value="{{ $acceptLits->monthlyconsumption }}">
                                                    <button type="submit" class="tick re-off"><i class="fa-solid fa-cart-shopping fa-cart-shopping1"></i></button>
                                                </form>
                                                <form action="{{ route('offer.remove', $acceptLits->id) }}" method="POST" class="removeForm">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $acceptLits->id }}">
                                                    <button type="submit" class="tick re-off"><i class="fa-solid fa-xmark"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            
                    @foreach ($customerPrices as $key => $priceItem)
                    @php
                        $product = $priceItem->product;
                    
                        if (!$product) {
                            continue;
                        }
                    
                        $gstRate = ($product->cgst ?? 0) + ($product->sgst ?? 0);
                    
                        $offerPrice = $product->sale_price_loose_pcs;
                    
                        $sellingPriceWithGst = $offerPrice * (1 + ($gstRate / 100));
                    
                        $mrp = $product->product_mrp ?? 0;
                    
                        $discount = $sellingPriceWithGst > $mrp
                            ? 0
                            : (($mrp - $sellingPriceWithGst) / $mrp) * 100;
                    
                        // Quantity logic
                        $quantity = 1;
                    @endphp
                    
                    <tr>
                       <td>{{ $enquiriesForAccept->count() + $loop->iteration }}</td>
                        <td><img src="uploads/{{ $product->image }}" class="enquiry-img"></td>
                        <td>{{ $product->product_name }}</td>
                    
                    <td>
                       
                            <span style="color: red">Loose (pcs.)</span>
                       
                    </td>
                    
                        <td>₹ {{ number_format($mrp, 2) }}</td>
                        <td>₹ {{ number_format($offerPrice, 2) }}</td>
                        <td>{{ number_format($discount, 2) }} %</td>
                    
                        <td>
                        <div class="d-flex">
                            <form action="{{ route('cart.create') }}" method="POST" class="add-to-cart-form">
                                @csrf
                    
                                <input type="hidden" name="product_types" value="2">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="{{ $quantity }}">
                    
                               
                                <input type="hidden" name="offer_price" value="{{ $offerPrice }}">
                    
                                <input type="hidden" name="mrp" value="{{ $mrp }}">
                                <input type="hidden" name="discount" value="{{ number_format($discount, 2) }}">
                    
                                <input type="hidden" name="expected_price_value" value="0">
                                <input type="hidden" name="monthlyconsumption" value="0">
                    
                    
                                <input type="hidden" name="price_source" value="customer">
                    
                                 <button type="submit" class="tick re-off"><i class="fa-solid fa-cart-shopping fa-cart-shopping1"></i></button>
                            </form>
                                    <form action="{{ route('customerprice.remove', $priceItem->id) }}"
                                              method="POST"
                                              class="removeForm">
                                            @csrf
                                            <button type="submit" class="tick re-off">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </form>
                        </div>    
                        </td>
                    </tr>
                    
                    @endforeach
                        </tbody>
                    </table>
                    <div id="noResultsMessage" class="text-center text-danger" style="display: none;">
                        <p>Product Not Found</p>
                    </div>
                </div>
            </div>

            <!-- Mobile Grid -->
               <div class="d-md-none">
        <div class="row g-2" id="priceListMobile">
            @foreach ($enquiriesForAccept as $key => $acceptLits)
                @php
                    $gstRate = $acceptLits->product->cgst + $acceptLits->product->sgst;
                    $sellingPriceWithGst = $acceptLits->offer_price * (1 + ($gstRate / 100));
                    $discount = $sellingPriceWithGst > $acceptLits->mrp
                        ? 0
                        : (($acceptLits->mrp - $sellingPriceWithGst) / $acceptLits->mrp) * 100;
                @endphp
                
                  @php
                    $needsPriceConfirmation = false;
                    $priceType = null;
                    $oldCost = $acceptLits->offer_price;
                    $newCost = null;

                    // CASE 2 — customer price (priority)
                    if (
                        $acceptLits->price_source !== 'customer' &&
                        isset($customerPrices[$acceptLits->product_id]) &&
                        $acceptLits->offer_price != $customerPrices[$acceptLits->product_id]->product_price
                    ) {
                        $needsPriceConfirmation = true;
                        $priceType = 'customer';
                        $newCost = $customerPrices[$acceptLits->product_id]->product_price;
                    }

                    // CASE 1 — product price
                    elseif (
                        $acceptLits->price_source !== 'customer' &&
                        $acceptLits->product &&
                        $acceptLits->cost_per_item != $acceptLits->product->cost_per_item
                    ) {
                        $needsPriceConfirmation = true;
                        $priceType = 'product';
                        $newCost = $acceptLits->product->cost_per_item;
                    }
                @endphp

            <div class="col-12">
        <div class="mobile-card shadow-sm border-0 rounded-3 position-relative">
            
            @if($discount > 0)
                <div class="ribbon-2">{{ number_format($discount) }}% OFF</div>
            @endif

            <div class="d-flex gap-3 align-items-start">
                <div class="flex-shrink-0">
                    <img src="{{ asset('uploads/' . $acceptLits->product->image) }}"
                        alt="{{ $acceptLits->product->product_name }}"
                        class="product-img rounded-2">
                </div>

                <div class="flex-grow-1">
                    <h6 class="product-title mb-1">{{ $acceptLits->product->product_name }}</h6>
                    <p class="small text-muted mb-1">
                        @if ($acceptLits->product_types == 1)
                            Pattern: Box
                        @elseif ($acceptLits->product_types == 2)
                            Pattern: Loose
                        @else
                            Loose/Box Not
                        @endif
                    </p>

                    <p class="price-text mb-1"><strong>Offer Price:</strong> ₹{{ number_format($acceptLits->offer_price, 2) }}</p>
                    <p class="price-text mb-2"><strong>MRP:</strong> ₹{{ number_format($acceptLits->mrp, 2) }}</p>

                    <div class="d-flex gap-2 flex-wrap">
                    @if ($acceptLits->product->status == 'inactive')
                    <span style="color: red;">This product is unavailable</span>
                    @elseif ($needsPriceConfirmation)
                        <i class="fa fa-info-circle"
                        style="font-size:41px;color:red;cursor:pointer;"
                        title="Click to confirm updated price"
                        onclick="openPriceConfirmModal(
                                {{ $acceptLits->id }},
                                '{{ $priceType }}',
                                {{ $oldCost }},
                                {{ $newCost }},
                                '{{ addslashes($acceptLits->product->product_name) }}'
                        )">
                        </i>

                    @else
                      <form action="{{ route('cart.create') }}" method="POST" class="add-to-cart-form me-2">
                            @csrf
                            <input type="hidden" name="product_types" value="{{ $acceptLits->product_types }}">
                            <input type="hidden" name="enquiry_id" value="{{ $acceptLits->id }}">
                            <input type="hidden" name="product_id" value="{{ $acceptLits->product_id }}">
                            <input type="hidden" name="quantity" value="{{ $acceptLits->product_types == 1 ? $acceptLits->product->carton_size : 1 }}">
                            <input type="hidden" name="offer_price" value="{{ $acceptLits->offer_price }}">
                            <input type="hidden" name="mrp" value="{{ $acceptLits->mrp }}">
                            <input type="hidden" name="discount" value="{{ number_format($discount, 2) }}">
                            <input type="hidden" name="expected_price_value" value="{{ $acceptLits->expected_price_value }}">
                            <input type="hidden" name="monthlyconsumption" value="{{ $acceptLits->monthlyconsumption }}">
                      <button type="submit" class="btn icon-btn tick re-off"><i class="fa-solid fa-cart-shopping fa-cart-shopping1"></i></button>
                        </form>

                       <form action="{{ route('offer.remove', $acceptLits->id) }}" method="POST" class="removeForm">
                            @csrf
                          <input type="hidden" name="product_id" value="{{ $acceptLits->id }}">
<button type="submit" class="btn icon-btn tick re-off"><i class="fa-solid fa-trash"></i></button>
                        </form>
                         @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

            @endforeach
            
             @foreach ($customerPrices as $priceItem)

@php
    $product = $priceItem->product;
    if (!$product) continue;

    $gstRate = ($product->cgst ?? 0) + ($product->sgst ?? 0);

    $offerPrice = $product->sale_price_loose_pcs;
    $mrp = $product->product_mrp ?? 0;

    $sellingPriceWithGst = $offerPrice * (1 + ($gstRate / 100));

    $discount = $sellingPriceWithGst > $mrp
        ? 0
        : (($mrp - $sellingPriceWithGst) / $mrp) * 100;

    $quantity = 1;
@endphp

<div class="col-12">
    <div class="mobile-card shadow-sm border-0 rounded-3 position-relative">

        @if($discount > 0)
            <div class="ribbon-2">{{ number_format($discount) }}% OFF</div>
        @endif

        <div class="d-flex gap-3 align-items-start">
            <div class="flex-shrink-0">
                <img src="{{ asset('uploads/' . $product->image) }}"
                     class="product-img rounded-2">
            </div>

            <div class="flex-grow-1">
                <h6 class="product-title mb-1">{{ $product->product_name }}</h6>

                <p class="small text-muted mb-1">
                  
                        Pattern: Loose
                  
                </p>

                <p class="price-text mb-1">
                    <strong>Offer Price:</strong> ₹{{ number_format($offerPrice, 2) }}
                </p>

                <p class="price-text mb-2">
                    <strong>MRP:</strong> ₹{{ number_format($mrp, 2) }}
                </p>

                <div class="d-flex gap-2 flex-wrap">

                
                    <form action="{{ route('cart.create') }}" method="POST" class="add-to-cart-form me-2">
                        @csrf

                        <input type="hidden" name="product_types" value="2">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="{{ $quantity }}">
                        <input type="hidden" name="offer_price" value="{{ $offerPrice }}">
                        <input type="hidden" name="mrp" value="{{ $mrp }}">
                        <input type="hidden" name="discount" value="{{ number_format($discount, 2) }}">
                        <input type="hidden" name="expected_price_value" value="0">
                        <input type="hidden" name="monthlyconsumption" value="0">
                        <input type="hidden" name="price_source" value="customer">

                        <button type="submit" class="btn icon-btn tick re-off">
                            <i class="fa-solid fa-cart-shopping fa-cart-shopping1"></i>
                        </button>
                    </form>
                    
                    <form action="{{ route('customerprice.remove', $priceItem->id) }}"
                        method="POST"
                        class="removeForm">
                        @csrf

                        <button type="submit" class="btn icon-btn tick re-off">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endforeach
        </div>
        
    
    <div id="noResultsMessage" class="text-center py-4 text-muted" style="display:none;">
        <i class="fa-solid fa-magnifying-glass mb-2" style="font-size:22px;"></i>
        <p>No results found.</p>
    </div>
    
    </div>
         
           @if ($enquiriesForAccept->isEmpty() && $customerPrices->isEmpty())
    <div class="text-center text-danger" id="noOffersMessage">
        <p>Dear Customer, no price offer has been accepted yet.</p>
    </div>
@else
    <div class="text-center text-danger" id="noResultsMessage" style="display:none;">
        <p>Product Not Found</p>
    </div>
@endif

        </div>
    </div>
</div>


<div class="modal fade" id="priceConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Confirm Price Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mt-2">Price has changed. Please confirm:</p>

                <table class="table table-sm">
                     <tr>
                        <th>Product</th>
                        <td id="productNameText" class="fw-bold text-primary"></td>
                    </tr>
                    <tr>
                        <th>Old Price</th>
                        <td id="oldPriceText"></td>
                    </tr>
                    <tr>
                        <th>New Price</th>
                        <td id="newPriceText"></td>
                    </tr>
                </table>
            </div>

            <div class="modal-footer">
                <button class="btn" style="background-color: #121286; color: #fff;" onclick="rejectPrice()">Reject</button>
                <button class="btn" style="background-color: #e97457; color: #fff;" onclick="acceptPrice()">Accept</button>
            </div>

        </div>
    </div>
</div>



<div class="tab-pane fade" id="v-pills-rejected" role="tabpanel" aria-labelledby="v-pills-rejected-tab">
    
@if ($cart->isEmpty())
      <div id="emptyCartMessage" style="margin-top: 30px; background: aliceblue; padding: 10px; font-weight: 500; font-size: 18px; letter-spacing: 0.5px;">
          <span>No items in cart.</span>
        </div>                       
    @endif

    <div class="orders-tab orders-tab-margin {{ $cart->isEmpty() ? ' d-none' : '' }}" >
        <div id="OderCart">
        <h4><span class="color-primary">Order Cart</span></h4>
      </div>
      <div class="row row1">

            <div class="col-md-8 my-2 cart-l">
                <div class="table-responsive d-none d-md-block">
                    <!-- Table layout for PC/tablets -->
                    <table class="table cart_table">
                        <thead>
                            <tr>
                                <th class="ct1">Sr.</th>
                                <th class="ct2">Product Name</th>
                                <th class="ct3">Order Qty</th>
                                <th class="ct4">Total Qty(Nos.)</th>
                                <th class="ct5">Total Amt (Basic)</th>
                                <th class="ct6"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($cart->isEmpty())
                                <tr id="emptyCartRow"> 
                                    <td colspan="6">No items in cart</td>
                                </tr>
                           
                            @endif
                            @php
                                $dates_checkin = [];
                                $subTotalAmt = 0;
                                $productDiscount = 0;
                                $DiscountValue = 0;
                                $totalDiscountValue = 0;
                                $TotalDiscountMainValue = 0;
                                $CGST = 0;
                                $SGST = 0;
                                $totalResult = 0;
                                $totalGrandTotal = 0;
                                $totalproductDiscount = 0;
                                $subTotal= 0;
                                $total_final_qty = 0;
                                $TotalGst = 0;
                                $cart_product_type = 0;
                                $result = 0;
                            @endphp

                            @foreach ($cart as $key => $cart_Items)
                                <tr class="cart-row{{ $key + 1 }}" >
                                    <td><span class="">{{ $key + 1 }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="uploads/{{ $cart_Items->product->image }}" class="enquiry-img">
                                            <p>{{ $cart_Items->product->product_name }}<br>
                                                <span class="text-center my-1"><b>Price: </b>₹{{ $cart_Items->offer_price }}</span> <br>
                                                 <span class="text-center "><b>Order Qty Type: </b>@if ($cart_Items->product_types == 1)
                                                    <span class="text-danger">Box</span>
                                                    @elseif ($cart_Items->product_types == 2)
                                                        <span class="text-danger">Loose</span>
                                                    @else
                                                        <span class="text-primary">Loose (pcs.)</span>
                                                    @endif
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        
                                        <div class="quantity">
                                            <button id="minusBtn{{ $key + 1 }}" @if ($cart_Items->count_value != 1)
                                                onclick="quantityMinus('{{ $key + 1 }}', '{{ $cart_Items->id }}', 'quantity__input{{ $key + 1 }}', 'cart-row{{ $key + 1 }}')"
                                                @endif class="quantity__minus"><span>-</span></button>
                                            <input type="hidden" name="product_id{{ $key + 1 }}" value="{{ $cart_Items->product->id }}">
                                            <input type="number" name="quantity{{ $key + 1 }}" id="quantity__input{{ $key + 1 }}" class="quantity__input quantity__input{{ $key + 1 }} total_qty_cart" value="{{ $cart_Items->count_value ?? 1 }}">
                                            <button onclick="quantityPlus('{{ $key + 1 }}', '{{ $cart_Items->id }}', 'quantity__input{{ $key + 1 }}', 'cart-row{{ $key + 1 }}')" class="quantity__plus"><span>+</span></button>
                                        </div>
                                        <span id="total{{ $key + 1 }}"></span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="total_qty_cart">{{ $cart_Items->total_qty }}</span>
                                    </td>

                                    <!-- <td><span class="total_qty_cart">{{ $cart_Items->total_qty }}</span></td> -->
                                    <td class="ct5" style="text-align: center;"><span class="total-amt-basic{{ $key + 1 }}">₹{{ $cart_Items->total_amt_basic }}</span></td>
                                    <td style="cursor: pointer;">
                                        <a onclick="removeCartItem('{{ $cart_Items->id }}')"><i class="fa-solid fa-trash"></i> </a>
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>
                    </table>
                </div>

                <!-- Grid layout for mobile -->
                <div class="d-md-none">
                    <div class="row">
                        @if ($cart->isEmpty())
                            <div class="col-12">
                                <div class="alert alert-info">No items in cart</div>
                            </div>
                        @endif

@php
    // Initialize variables before the loop
    $subTotalAmt = 0;
    $totalproductDiscount = 0;
    $TotalDiscountMainValue = 0;
    $totalGrandTotal = 0;
    $totalGST = 0;
    $dates_checkin = [];
@endphp
                        @foreach ($cart as $key => $cart_Items)
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                    <div class="row">
                                    <div class="col-sm-4">
                                      <img src="uploads/{{ $cart_Items->product->image }}"  class="card-img-top newpriceimage5" alt="Product Image"></div>
                                            <div class="col-sm-8 p-2 cart-row{{ $key + 1 }}">
                                                <h5 class="card-title"><strong>{{ $cart_Items->product->product_name }}</strong></h5>
                                                <p class="card-text">
                                                    <span class="text-center my-1"><b>Price: </b>₹{{ $cart_Items->offer_price }}</span><br>
                                                    <b>Order Qty:</b>
                                                    @if ($cart_Items->product_types == 1)
                                                        <span class="text-danger my-1">Box</span>
                                                    @elseif ($cart_Items->product_types == 2)
                                                        <span class="text-danger">Loose</span>
                                                    @else
                                                        <span class="text-primary">Loose (pcs.)</span>
                                                    @endif
                                                </p>
                                                <div class="row">
                                    <div class="col-6 col-md-2">
                                    <div class="quantity">
                                    <!--<button id="minusBtn{{ $key + 1 }}" @if ($cart_Items->count_value != 1)-->
                                    <!--    onclick="quantityMinus('{{ $key + 1 }}', '{{ $cart_Items->id }}', 'quantity__input{{ $key + 1 }}', 'cart-row{{ $key + 1 }}')"-->
                                    <!--    @endif class="quantity__minus"><span>-</span></button>-->
                                        
<button 
    id="minusBtn{{ $key + 1 }}"
    @if ($cart_Items->count_value != 1)
        onclick="quantityMinus('{{ $key + 1 }}', '{{ $cart_Items->id }}', 'quantity__input{{ $key + 1 }}', 'cart-row{{ $key + 1 }}')"
    @endif
    class="quantity__minus">
    <span>-</span>
</button>


                                    <input type="hidden" name="product_id{{ $key + 1 }}" value="{{ $cart_Items->product->id }}">
                                    <input type="number" name="quantity{{ $key + 1 }}" id="quantity__input{{ $key + 1 }}" class="quantity__input quantity__input{{ $key + 1 }} total_qty_cart" value="{{ $cart_Items->count_value ?? 1 }}">
                                   
                                    <button onclick="quantityPlus('{{ $key + 1 }}', '{{ $cart_Items->id }}', 'quantity__input{{ $key + 1 }}', 'cart-row{{ $key + 1 }}')" class="quantity__plus"><span>+</span></button>
                                </div>
                                    </div>
                                    <div class="col-2 col-md-2">
                                    <div style="cursor: pointer;">
                                        <a onclick="removeCartItem('{{ $cart_Items->id }}')"> <i class="fa-solid fa-trash" style="width:25px; height:25px; line-height: 26px; font-size: 11px;"></i> </a>
                                    </div>
                                    </div>
                                </div>
                                             
                                                <span ><b>Total Qty: </b> <span class="total_qty_cart" style="margin-top: 3px;">{{ $cart_Items->total_qty }}</span> </span><br>
                                             
                                                <div class="newvaluechanged">
                                                <span class="total-amt-basic{{ $key + 1 }}"><b>Total Amt (Basic):</b> ₹{{ $cart_Items->total_amt_basic }}</span>
                                                 </div>
    


                                            </div>
                                            </div>
                                    </div>
                                </div>
                            </div>

                        
    @php
        $dates_checkin[] = $cart_Items->created_at;
        $subTotal = $cart_Items->total_amt_basic;
        $productDiscount = $cart_Items->product->total_discount > 0 ? ($subTotal * $cart_Items->product->total_discount) / 100 : 0;
        $DiscountValue = $subTotal - $productDiscount;

        // Calculate GST for this product (on the ORIGINAL subtotal, not discounted value)
        $CGST = $cart_Items->product->cgst;
        $SGST = $cart_Items->product->sgst;
        $TotalGstPerProduct = $CGST + $SGST;
        $productGST = ($subTotal * $TotalGstPerProduct) / 100; // GST on original subtotal

        // Accumulate totals
        $TotalDiscountMainValue += $DiscountValue;
        $totalGST += $productGST;
        $totalproductDiscount += $productDiscount;
        $subTotalAmt += $subTotal;
        $totalGrandTotal += $DiscountValue;
    @endphp
@endforeach

@php
    // Final calculation
    $totalDiscountValue = $subTotalAmt + $totalGST;
@endphp


                    </div>
                </div>

            </div>




            
            <div id="orderSummarySection" class="@if($cart->isEmpty()) d-none @endif col-md-4 my-2 p-0">
            
                <div class="cart-right  mb-3">

                    <div class="order-summary ">
                        <h4 class="my-3"><span class="color-dark">Order</span> Summary</h4>
                        <div class="row  order-row">
                            <div class="col-md-8 col-sm-6">
                                <p>Subtotal(Basic)</p>
                                <p><span class="secondary-color">Product Discount</span></p>
                                <p>GST + Cess</p>

                            </div>
                            <div class="col-md-4 text-end col-sm-6">
                                <p class="subtotal-value">₹ {{ number_format($subTotalAmt,2) }}</p>
                                <p><span class="secondary-color product-discount-value">-
                                        ₹{{ number_format(  $totalproductDiscount,2) }}</span></p>
                                <p class="gst-value">+ ₹{{ number_format(  $totalGST,2) }}</p>

                            </div>
                        </div>
                        <div class="row mt-2 order-row">
                            <div class="col-md-8 col-sm-6">
                                <p></p>
                                <p class=""><span>Grand Total</span> </p>
                            </div>
                            <div class="col-md-4 text-end col-sm-6 ">
                                <p><span class="grand-total-value">₹ {{ number_format ( $totalDiscountValue,2) }}</span>
                                </p>
                                <p class="taxes">(inclusive all taxes)</p>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12 bordered p-2 my-2">

                            <div class="row">
                                <div class="col-md-6">
                                    <h3>PROMO CODE</h3>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                            <div id="promo-i" >
                                <form id="couponForm">
                                    <input maxlength="12" class="form-control couponCode" placeholder="Coupon code.." name="couponCode" id="couponCode" readonly>
                                    <span class="validationCoupon2 text-danger"></span>
                                    <span class="validationCoupon text-danger"></span>
                                    <!--<div class="dispay-content" id="couponToggle" onclick="toggleCouponList()">-->
                                    <!--    <label for="" class="float-right"><u>Views Coupons</u></label>-->
                                    <!--</div>-->
 <ul id="couponList" class="couponList bg-white p-2">
    @foreach ($coupon as $item)
        <li 
            class="coupon-item d-flex justify-content-between align-items-center @if ($item->max_price > $totalDiscountValue) disabled @endif" 
            data-max-price="{{ $item->max_price }}" 
            style="margin-bottom: 10px; padding: 8px; border-bottom: 1px solid #ddd;"
        >
            <div>
                {{ $item->coupon_name }} - Up to {{ $item->discount_amount }} Off.
                <br>
                <a href="javascript:void(0);" class="view-more-link" onclick="toggleDescription(this)">View More</a>
                <div class="coupon-description p-3" style="display: none;">
                    <span><b>Min Order: </b> {{ $item->max_price }} </span> <br>
                    <span><b>Valid Till: </b>{{ $item->end_date }}</span> <br>
                    <span><b>Description: </b> {{ $item->description }}</span>
                </div>
            </div>
           <button type="button" class="btn btn-success btn-sm rounded-pill copy-code" 
                    data-coupon-code="{{ $item->coupon_code }}">
                Apply Code
            </button>

           
            
        </li>
    @endforeach
    <div class="dic-div mb-2 mt-4">
        <span class="secondary-color total-savings-value">Get your coupon on this order</span>
    </div>
</ul>

                                  
                                </form>
                            </div>


                                <button type="submit"
                                class="btn theme-bg-color btn-md text-white fw-bold mt-md-4 w-100"
                                data-bs-toggle="modal" data-bs-target="#checkout">
                                Checkout
                            </button>


                            <div class="modal fade" id="checkout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content custom-modal-width">
                                    <div class="modal-header">
                                    <h5 class="modal-title order-h5" id="exampleModalLabel">Select Outlet</h5> 
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <div class="table-responsive">
                                      @if($userData->isEmpty())
                                        <div class="no-data-message">
                                            <p style="font-size: 18px;">Click Below Button to Add Outlet and Verified</p>
                                        
                                        </div>
                                    @else
                                        <table class="table"  id="outletTable">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>User Name</th>
                                                    <th>Outlet Name</th>
                                                    <th>Location</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($userData as $user)
                                                <tr for="rowData" onclick="selectOutlet({{ $user->id }})">
                                                    <td>
                                                        @if($user->verified_status == 'verified')
                                                            <input type="radio" name="outlet" id="rowData{{ $user->id }}">
                                                        @else
                                                            <input type="radio" name="outlet" disabled="true">
                                                        @endif
                                                    </td>
                                                 <td class="d-none d-md-table-cell">{{ $user->name }}</td>
                                                    <td>{{ $user->outlet_name }}</td>
                                                    <td>
                                                        @if(strlen($user->location) > 10)
                                                            <span id="short-location-{{ $user->id }}">{{ Str::limit($user->location, 20, '...') }}</span>
                                                            <span id="full-location-{{ $user->id }}" style="display:none;">{{ $user->location }}</span>
                                                            <a href="javascript:void(0)" class="text-primary" id="view-link-{{ $user->id }}" onclick="toggleLocation({{ $user->id }})">
                                                                View More
                                                            </a>
                                                        @else
                                                            {{ $user->location }}
                                                        @endif
                                                    </td>



                                                    <td style="white-space: nowrap">
                                                        @if($user->verified_status == 'verified')
                                                            <div class="customer_status">
                                                                <div class="customer_bage">
                                                                    <img src="https://cdn.shopify.com/s/files/1/0566/8241/4246/t/11/assets/icon-verified-1662629893290.png?v=1662629894" alt="">
                                                                </div>
                                                                <div class="customer_status_content">Verified</div>
                                                            </div>
                                                        @else
                                                            <a href="verify-outlet/{{ $user->id }}">
                                                                <div class="customer_status">
                                                                    <div class="customer_bage">
                                                                        <img src="https://cdn3d.iconscout.com/3d/premium/thumb/unverified-security-9031295-7516457.png?f=webp" alt="">
                                                                    </div>
                                                                    <div class="customer_status_content text-danger">Unverified</div>
                                                                </div> Click to verify
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif

                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                    @if($userData->isEmpty())
                                        <!--<button onclick="window.location.href='{{ url('/profile') }}'" class="btn theme-bg-color btn-md text-white fw-bold mt-md-4">-->
                                        <!--    <i class="fa fa-plus" aria-hidden="true"></i> &nbsp; Add Outlet-->
                                        <!--</button>-->
                                        <button type="button"
                                            class="btn theme-bg-color btn-md text-white fw-bold mt-md-4"
                                            onclick="openOutletModal()">
                                            <i class="fa fa-plus"></i> Add Outlet
                                        </button>
                                        
                                    @else
                                    <!--<button onclick="window.location.href='{{ url('/profile') }}'" class="btn theme-bg-color btn-md text-white fw-bold mt-md-4">-->
                                    <!--    <i class="fa fa-plus" aria-hidden="true"></i> &nbsp; Add Outlet-->
                                    <!--    </button>-->
                                    
                                       <button type="button"
                                            class="btn theme-bg-color btn-md text-white fw-bold mt-md-4"
                                            onclick="openOutletModal()">
                                            <i class="fa fa-plus"></i> Add Outlet
                                        </button>
                                        
                                        <button onclick="redirectToCheckout()" class="btn theme-bg-color btn-md text-white fw-bold mt-md-4 checkout-btn" disabled>
                                            <i class="fa fa-money" aria-hidden="true"></i> Checkout
                                        </button>
                                    @endif
                                </div>

                                </div>
                                <input type="hidden" id="selectedRowData" name="selectedRowData">

                            </div>
                            </div>
                            
                            <div class="modal location-modal fade theme-modal" id="locationModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-full screen-sm-down">
        <div class="modal-content modal-cust" id="mobileBox">

            <div class="modal-header">
                <h5 class="modal-title indexh5 mb-2">Outlet Form</h5>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
   <i class="fa-solid fa-x"></i>
              </button>
            </div>

            <div class="modal-body">

                <form id="outletForm">

                    <div class="location-list">
                        <div class="search-input">

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text"
                                           id="o_name"
                                           class="form-control mb-3"
                                           placeholder="Enter Your Name"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <input type="text"
                                           id="o_outlet_name"
                                           class="form-control mb-3"
                                           placeholder="Enter Your Outlet Name"
                                           required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="tel"
                                           id="o_mobile"
                                           class="form-control mb-3"
                                           placeholder="Enter Your Mobile Number"
                                           maxlength="10"
                                           pattern="[6-9]{1}[0-9]{9}"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <input type="email"
                                           id="o_email"
                                           class="form-control mb-3"
                                           placeholder="abc@gmail.com"
                                           required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text"
                                           id="o_location"
                                           class="form-control mb-3"
                                           placeholder="Enter Your Location Name"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <input type="text"
                                           id="o_pincode"
                                           class="form-control mb-3"
                                           placeholder="Enter Your Pincode"
                                           required>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div id="o_messageBox" class="mb-3 error-message"></div>
                     <div id="loader" class="text-center mt-3" style="display:none;">
                        <img src="/assets/Loading_2.gif" width="50">
                    </div>

                    <button type="button"
                            class="btn red-btn w-100 fw-bold"
                            id="saveOutletBtn">
                        Add Outlet
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>


                        
                      </div>
                    </div>
                </div>
            </div>
          
        </div>
    </div>
</div>




<div class="tab-pane fade" id="v-pills-cart" role="tabpanel" aria-labelledby="v-pills-cart-tab">...
</div>

</div>
</div>

</div>
</section>
</div>
</div>
</div>
</div>



</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function filterTable() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    const searchValue = searchInput.value.trim().toLowerCase();
    let foundDesktop = false;
    let foundMobile = false;


    const table = document.getElementById('priceTable');
    if (table) {
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchValue)) {
                row.style.display = '';
                foundDesktop = true;
            } else {
                row.style.display = 'none';
            }
        });
    }

   
    const cardContainer = document.getElementById('priceListMobile');
    if (cardContainer) {
        const cards = cardContainer.querySelectorAll('.mobile-card'); 
        cards.forEach(card => {
            const cardText = card.textContent.toLowerCase();
            const cardWrapper = card.closest('.col-12') || card.parentElement; 

            if (cardText.includes(searchValue)) {
                cardWrapper.style.display = '';
                foundMobile = true;
            } else {
                cardWrapper.style.display = 'none';
            }
        });
    }

  
    const noResultsMessage = document.getElementById("noResultsMessage");
    if (noResultsMessage) {
        if (searchValue === "") {
            noResultsMessage.style.display = "none";
        } else if (foundDesktop || foundMobile) {
            noResultsMessage.style.display = "none";
        } else {
            noResultsMessage.style.display = "block";
        }
    }
}


document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filterTable);
    }
});
</script>




<script>
$('#ajax-cart-form').submit(function(event) {
    event.preventDefault(); // Prevent default form submission

    var formData = $(this).serialize(); // Serialize form data

    $.ajax({
        url: '{{ route('cart.create') }}', // Your route here
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                // Handle success response
                toastr.success(response.message); // Display success message using toastr
                // Optionally, update the UI to reflect the added item
            } else {
                // Handle error response
                toastr.error(response.message); // Display error message using toastr
            }
        },
        error: function(xhr, status, error) {
            // Handle error response
            console.error(xhr.responseText);
            toastr.error('An error occurred while adding the item to the cart.'); // Display generic error message using toastr
        }
    });
});

</script>
<script>
function toggleLocation(userId) {
    // Get the short and full location elements
    const shortLocation = document.getElementById('short-location-' + userId);
    const fullLocation = document.getElementById('full-location-' + userId);
    const viewLink = document.getElementById('view-link-' + userId);

    // Toggle visibility
    if (fullLocation.style.display === "none") {
        fullLocation.style.display = "inline";  
        shortLocation.style.display = "none";   
        viewLink.innerHTML = "View Less";      
    } else {
        fullLocation.style.display = "none";   
        shortLocation.style.display = "inline"; 
        viewLink.innerHTML = "View More";
    }
}

</script>

<script>
    window.csrfToken = "{{ csrf_token() }}";
</script>


<script>


function syncMonthlyConsumption() {

    $('[id^="monthlyconsumption"]').each(function () {
        let id = $(this).attr('id').replace('monthlyconsumption', '');
        
        let desktop = $('#monthlyconsumption' + id + ':visible');
        let mobile  = $('[id="monthlyconsumption' + id + '"]:hidden');

       
        if (desktop.length && desktop.val().trim() !== "") {
            mobile.val(desktop.val().trim());
        }

       
        if (mobile.length && mobile.val().trim() !== "") {
            desktop.val(mobile.val().trim());
        }
    });
}


$('#submitEnquiryButton').on('click', function() {
 
    if (!$(this).attr('disabled')) {
     
        submitEnquiry();

     
        $(this).attr('disabled', true).css('cursor', 'not-allowed');

       
        $(this).text('Processing...');
    }
});

function submitEnquiry() {
    
  syncMonthlyConsumption();
  
  var formData = $('#enquiryForm').serialize();
    $.ajax({
        url: '/enquiry/store',
        method: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response && response.success) {
                Swal.fire({
                    title: "Your enquiry has been submitted!",
                    text: "We'll get back to you as soon as possible.",
                    icon: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Okay",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/homepage';
                    }
                });

            } else {
                Swal.fire({
                    title: "Oops!",
                    text: "Something went wrong. Please try again later.",
                    icon: "error"
                });
            }
        },
        error: function(xhr, status, error) {
            var errorMessage = xhr.responseText || 'Internal Server Error';
            Swal.fire({
                title: "Error",
                text: errorMessage,
                icon: "error"
            });
        }
    });
}


$(document).ready(function() {
    $('.checkout_btn_datewise').click(function() {
        var datewisecheckout = $('#datewisecheckout').val();
        localStorage.setItem('datewisecheckout', datewisecheckout);
        $('#checkout').modal('show');
        $('#checkout1').modal('hide');
    });
});


    var noCount = {{ $noCount }};
    var yesCount = {{ $yesCount }};
    var activeSection = null; // Track the active section (Offer or Reoffer)


$(document).ready(function() {
    

    console.log("Initial count for Approved - Offer:", noCount, "Reoffer:", yesCount);

    // Detect which section is active when user clicks a tab
    $('.tabs1').click(function() {
        activeSection = $(this).attr("id"); // Get the ID of the clicked tab
        console.log("Active Section Set:", activeSection);
    });

    // $('.acceptButton').click(function(e) {
    //     e.preventDefault();

    //     var formData = $(this).closest('form').serialize();
    //     var isOfferSection = $(this).closest('form').hasClass('offerForm'); 
    //     var isReofferSection = $(this).closest('form').hasClass('reofferForm'); 

    //     console.log("Submitting form... Offer:", isOfferSection, "Reoffer:", isReofferSection);

    //     $.ajax({
    //         url: $(this).closest('form').attr('action'),
    //         type: 'POST',
    //         data: formData,
    //         success: function(response) {
    //             console.log("Response received:", response);
                
    //              showToast('success', response.message);

    //             if (response.code == 300) {
    //                 showToast('warning', response.message);
    //             } else {
    //                 if (isOfferSection) {
    //                     noCount -= 1;
    //                     console.log("Remaining Offers:", noCount);
    //                     if (noCount <= 0) {
    //                         redirectToHomepage();
    //                         return; // Prevents reload after redirect
    //                     }
    //                 }

    //                 if (isReofferSection) {
    //                     yesCount -= 1;
    //                     console.log("Remaining Reoffers:", yesCount);
    //                     if (yesCount <= 0) {
    //                         redirectToHomepage();
    //                         return; // Prevents reload after redirect
    //                     }
    //                 }




    //                 // If not redirecting, reload the page
    //                 // console.log("Reloading page...");
    //                 // setTimeout(function() {
    //                     // location.reload();
    //                 // }, 500);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error("AJAX Error:", xhr.responseText);
    //             Swal.fire({
    //                 title: "Error",
    //                 text: xhr.responseText || 'Internal Server Error',
    //                 icon: "error"
    //             });
    //         }
    //     });
    // });


function needsPriceConfirmationJS(p) {
    let needsConfirmation = false;
    let priceType = null;
    let newCost = null;

  
    if (
        p.price_source !== 'customer' &&
        p.customer_price !== null &&
        parseFloat(p.offer_price) !== parseFloat(p.customer_price)
    ) {
        needsConfirmation = true;
        priceType = 'customer';
        newCost = p.customer_price;
    }

    
    else if (
        p.price_source !== 'customer' &&
        parseFloat(p.cost_per_item) !== parseFloat(p.product.cost_per_item)
    ) {
        needsConfirmation = true;
        priceType = 'product';
        newCost = p.product.cost_per_item;
    }

    return { needsConfirmation, priceType, newCost };
}



function appendToPriceList(p) {
   
const noOffers = document.getElementById('noOffersMessage');
if (noOffers) noOffers.style.display = "none";

// also hide "product not found" message
const noResults = document.getElementById('noResultsMessage');
if (noResults) noResults.style.display = "none";

 const priceCheck = needsPriceConfirmationJS(p);
 let actionHtml = '';

  if (p.status === 'inactive') {
        actionHtml = `<span style="color:red;">This product is unavailable</span>`;
    }

     else if (priceCheck.needsConfirmation) {
        actionHtml = `
            <i class="fa fa-info-circle"
               style="font-size:41px;color:red;cursor:pointer;"
               title="Click to confirm updated price"
               onclick="openPriceConfirmModal(
                    ${p.id},
                    '${priceCheck.priceType}',
                    ${p.offer_price},
                    ${priceCheck.newCost},
                    '${p.product.product_name.replace(/'/g, "\\'")}'
               )">
            </i>
        `;
    }
     else {
        actionHtml = `
            <form action="/cart/create" method="POST" class="add-to-cart-form">
                <input type="hidden" name="_token" value="${window.csrfToken}">
                <input type="hidden" name="product_types" value="${p.product_types}">
                <input type="hidden" name="enquiry_id" value="${p.id}">
                <input type="hidden" name="product_id" value="${p.product_id}">
                <input type="hidden" name="quantity"
                       value="${p.product_types == 1 ? p.product.carton_size : 1}">
                <input type="hidden" name="offer_price" value="${p.offer_price}">
                <input type="hidden" name="mrp" value="${p.mrp}">
                <input type="hidden" name="discount" value="${p.discount}">
                <input type="hidden" name="expected_price_value"
                       value="${p.expected_price_value ?? ''}">
                <input type="hidden" name="monthlyconsumption"
                       value="${p.monthlyconsumption ?? ''}">

                <button type="submit" class="tick re-off">
                    <i class="fa-solid fa-cart-shopping fa-cart-shopping1"></i>
                </button>
            </form>

            <form action="/offer/remove/${p.id}" method="POST">
                <input type="hidden" name="_token" value="${window.csrfToken}">
                <input type="hidden" name="product_id" value="${p.id}">
                <button type="submit" class="tick re-off">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </form>
        `;
    }




let newRow = `
<tr>
<td class="w3-7">NEW</td>
<td class="w3-8"><img src="/uploads/${p.product.image}" class="enquiry-img"></td>
<td class="w3-9 w-f">${p.product.product_name}</td>
<td>
${p.product_types == 1 
? `<span style="color:red">Carton Box : ${p.product.carton_size ?? 0}</span>` 
: p.product_types == 2 
? `<span style="color: red">Loose (pcs.)</span>` 
: `<span style="color: blue">Loose/Box Not</span>`}
</td>



<td>₹ ${p.mrp}</td>
            <td>₹ ${p.offer_price}</td>
            <td>${p.discount}%</td>
            <td class="w3-10">
                <div class="d-flex flex-column">
                    <div class="d-flex">
                        ${actionHtml}
                    </div>
                </div>
            </td>

</tr>
`;
    $('#priceTable tbody').prepend(newRow);

    let newCard = `
        <div class="col-12">
            <div class="card mb-3">
                <div class="row">
                    <div class="col-4">
                        <img src="/uploads/${p.product.image}" class="card-img-top" style="height:100px;width:100px;">
                    </div>
                    <div class="col-8 p-2">
                        <h5><strong>${p.product.product_name}</strong></h5>
                        <p>${p.product_types == 1 ? "Pattern: Box" : p.product_types == 2 ? "Pattern: Loose" : "Loose/Box Not"}</p>
                        <div class="ribbon-2">${p.discount}% OFF</div>
                        <p><strong>Offer Price:</strong> ₹ ${p.offer_price}</p>
                        <p><strong>MRP:</strong> ₹ ${p.mrp}</p>
                        <div class="d-flex">
                            <form action="/cart/create" method="POST" class="add-to-cart-form me-2">
                                <input type="hidden" name="_token" value="${window.csrfToken}">
                                <input type="hidden" name="product_types" value="${p.product_types}">
                                <input type="hidden" name="enquiry_id" value="${p.id}">
                                <input type="hidden" name="product_id" value="${p.product_id}">
                                <input type="hidden" name="quantity" value="${p.product_types == 1 ? p.product.carton_size : 1}">
                                <input type="hidden" name="offer_price" value="${p.offer_price}">
                                <input type="hidden" name="mrp" value="${p.mrp}">
                                <input type="hidden" name="discount" value="${p.discount}">
                                <input type="hidden" name="expected_price_value" value="${p.expected_price_value}">
                                <input type="hidden" name="monthlyconsumption" value="${p.monthlyconsumption}">
                                <button type="submit" class="btn btn-theme tick re-off"><i class="fa-solid fa-cart-shopping"></i></button>
                            </form>
                            <form action="/offer/remove/${p.id}" method="POST">
                                <input type="hidden" name="_token" value="${window.csrfToken}">
                                <input type="hidden" name="product_id" value="${p.id}">
                                <button type="submit" class="btn btn-danger tick re-off"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    $('#v-pills-price .d-md-none .row').prepend(newCard);
}




$('.acceptButton').click(function(e) {
    e.preventDefault();

    var $button = $(this);
    var $form = $button.closest('form');
    var formData = $form.serialize();
    var isOfferSection = $form.hasClass('offerForm'); 
    var isReofferSection = $form.hasClass('reofferForm'); 

    $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: formData,
        success: function(response) {
            showToast('success', response.message);

            if (response.code == 300) {
                showToast('warning', response.message);
            } else {
                if (isOfferSection) {
                    noCount -= 1;
                    $('#v-pills-offer-tab').text("New Offer (" + noCount + ")");
                    $('#v-pills-offer-tab-mobile').text("New Offer (" + noCount + ")");
                }

                if (isReofferSection) {
                    yesCount -= 1;
                    $('#v-pills-offer-tab2').text("Reoffer (" + yesCount + ")");
                    $('#v-pills-reoffer-tab-mobile').text("Reoffer (" + yesCount + ")");
                }

                // Update Offer List total
                var totalCount = noCount + yesCount;
                $('#v-pills-offer-dropdown-toggle .countSpan').text(totalCount);

                // Update My Price List count
                var priceListCount = parseInt($('#v-pills-price-tab .countSpan').text()) || 0;
                $('#v-pills-price-tab .countSpan').text(priceListCount + 1);

                // Remove row/card
                $form.closest('tr, .card').fadeOut(400, function() { $(this).remove(); });

                // Append to My Price List
                if (response.acceptedProduct) {
                     console.log("Accepted product:", response.acceptedProduct);
                    appendToPriceList(response.acceptedProduct);
                   
                }

                // Redirect if nothing left
                if (noCount <= 0 && yesCount <= 0) {
                    redirectToHomepage();
                }
            }
        },
        error: function(xhr) {
            Swal.fire({
                title: "Error",
                text: xhr.responseText || 'Internal Server Error',
                icon: "error"
            });
        }
    });
});







// Handle Add to Cart dynamically
$(document).on("submit", ".add-to-cart-form", function (e) {
    e.preventDefault();

    let form = $(this);
    let formData = form.serialize();

    $.ajax({
        url: form.attr("action"),
        method: "POST",
        data: formData,
        success: function (res) {
            if (res.success) {
                // 🟢 Case: Product already in cart
                if (res.already_in_cart) {
                    showToast("info", "Item already exists in cart"); 
                } 
                // 🟢 Case: Freshly added product
                else {
                    appendToCart(res.cart_item);
                    showToast("success", "Item added to cart successfully");

                    let cartCount = parseInt($('#v-pills-rejected-tab .countSpan').text()) || 0;
                    cartCount += 1;
                    $('#v-pills-rejected-tab .countSpan').text(cartCount);
                }

                // Update summary values only when **new item is added**
                if (!res.already_in_cart) {
                    $(".subtotal-value").text("₹ " + res.calculated.subtotal.toFixed(2));
                    $(".product-discount-value").text("- ₹" + res.calculated.discount.toFixed(2));
                    $(".gst-value").text("+ ₹" + res.calculated.gst.toFixed(2));
                    $(".grand-total-value").text("₹ " + res.calculated.grand_total.toFixed(2));

                    $("#orderSummarySection").removeClass("d-none");
                }
            } else {
                showToast("warning", "Failed to add to cart");
            }
        },
        error: function () {
            showToast("warning", "Something went wrong");
        }
    });
});




// Append new cart row dynamically
function appendToCart(c) {
    $("#emptyCartMessage").remove();
    $("#emptyCartRow").remove();
    $(".orders-tab").removeClass("d-none");

    let cartId = c.id;  // use real cart_id
    let countValue = c.count_value ?? 1;

    // Table row
    let newRow = `
    <tr id="cart-row${cartId}">
        <td>NEW</td>
        <td>
            <div class="d-flex align-items-center">
                <img src="/uploads/${c.product.image}" class="enquiry-img">
                <p>${c.product.product_name}<br>
                    <span class="text-center my-1"><b>Price: </b>₹${c.offer_price}</span><br>
                    <span class="text-center"><b>Order Qty Type: </b>
                        ${c.product_types == 1 ? '<span class="text-danger">Box</span>' 
                        : c.product_types == 2 ? '<span class="text-danger">Loose</span>' 
                        : '<span class="text-primary">Loose (pcs.)</span>'}
                    </span>
                </p>
            </div>
        </td>
        <td>
            <div class="quantity">
                <button class="quantity__minus" onclick="dynamicQuantityMinus('${cartId}', ${c.offer_price})"><span>-</span></button>
                <input type="number" id="quantity__input${cartId}" class="quantity__input" value="${countValue}" min="1" data-count="${countValue}">
                <button class="quantity__plus" onclick="dynamicQuantityPlus('${cartId}', ${c.offer_price}, ${countValue})"><span>+</span></button>
            </div>
            <span id="total${cartId}"></span>
        </td>
        <td style="text-align: center;" id="total_qty${cartId}">${c.total_qty}</td>
        <td class="ct5" style="text-align: center;" id="total_amt${cartId}">₹${c.total_amt_basic}</td>
        <td><a onclick="removeCartItem('${cartId}')"><i class="fa-solid fa-trash"></i></a></td>
    </tr>`;

    $(".cart_table tbody").prepend(newRow);

    // Mobile card view
    let newCard = `
    <div class="col-12 mb-3" id="cart-card${cartId}">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <img src="/uploads/${c.product.image}" class="card-img-top newpriceimage5">
                    </div>
                    <div class="col-sm-8 p-2">
                        <h5><strong>${c.product.product_name}</strong></h5>
                        <p><b>Price: </b>₹${c.offer_price}</p>
                        <p><b>Order Qty:</b> ${c.product_types == 1 ? 'Box' : c.product_types == 2 ? 'Loose' : 'Loose (pcs.)'}</p>
                        <div class="quantity mt-2">
                            <button class="quantity__minus" onclick="dynamicQuantityMinus('${cartId}', ${c.offer_price}, true)"><span>-</span></button>
                            <input type="number" id="quantity__inputMob${cartId}" class="quantity__input" value="${countValue}" min="1" data-count="${countValue}">
                            <button class="quantity__plus" onclick="dynamicQuantityPlus('${cartId}', ${c.offer_price}, ${countValue}, true)"><span>+</span></button>
                        </div>
                        <span id="totalMobQty${cartId}">}</span><br>
                        <span class="total_qty_cart"><b>Total Qty:</b> ${c.total_qty}</span><br>
                        <span><b>Total Amt (Basic):</b> ₹<span id="totalMobAmt${cartId}">${c.total_amt_basic}</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
    $("#v-pills-rejected .d-md-none .row").prepend(newCard);
}







});





// $(document).ready(function () {
//     var noCount = {{ $noCount }};
//     var yesCount = {{ $yesCount }};
//     var activeSection = null;

//     console.log("Initial count for reject - Offer:", noCount, "Reoffer:", yesCount);

//     // Detect active section when a tab is clicked
//     $('.tabs1').click(function () {
//         activeSection = $(this).attr("id");
//         console.log("Active Section Set:", activeSection);
//     });

//     $('.rejectButton').click(function (e) {
//         e.preventDefault();

//         var form = $(this).closest('form');
//         var formData = form.serialize();
//         var isOfferReject = form.hasClass('offerRejectForm');
//         var isReofferReject = form.hasClass('reofferRejectForm');

//         console.log("Submitting reject form... Offer Reject:", isOfferReject, "Reoffer Reject:", isReofferReject);

//         $.ajax({
//             url: form.attr('action'),
//             type: 'POST',
//             data: formData,
//             success: function (response) {
//                 console.log("Response received:", response);

//                 if (response.redirect) {
//                     window.location.href = response.redirect;
//                     return;
//                 }
//                  showToast('success', response.message);

//                 if (response.code == 300) {
//                     showToast('warning', response.message);
//                 } else {
//                     if (isOfferReject) {
//                         noCount -= 1;
//                         console.log("Remaining Offers:", noCount);
//                         if (noCount <= 0) {
//                             window.location.href = '/homepage'; // Direct Redirect
//                             return;
//                         }
//                     }

//                     if (isReofferReject) {
//                         yesCount -= 1;
//                         console.log("Remaining Reoffers:", yesCount);
//                         if (yesCount <= 0) {
//                             window.location.href = '/homepage'; // Direct Redirect
//                             return;
//                         }
//                     }

//                     // If not redirecting, reload the page
//                     console.log("Reloading page...");
//                     setTimeout(function () {
//                         location.reload();
//                     }, 500);
//                 }
//             },
//             error: function (xhr, status, error) {
//                 console.error("AJAX Error:", xhr.responseText);
//                 Swal.fire({
//                     title: "Error",
//                     text: xhr.responseText || 'Internal Server Error',
//                     icon: "error"
//                 });
//             }
//         });
//     });
// });


$(document).ready(function () {
   

    console.log("Initial count for reject - Offer:", noCount, "Reoffer:", yesCount);

    // Track active section
    $('.tabs1').click(function () {
        activeSection = $(this).attr("id");
        console.log("Active Section Set:", activeSection);
    });

    $('.rejectButton').click(function (e) {
        e.preventDefault();

        var $button = $(this);
        var $form = $button.closest('form');
        var formData = $form.serialize();
        var isOfferReject = $form.hasClass('offerRejectForm');
        var isReofferReject = $form.hasClass('reofferRejectForm');

        console.log("Submitting reject form... Offer Reject:", isOfferReject, "Reoffer Reject:", isReofferReject);

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log("Response received:", response);

                showToast('success', response.message);

                if (response.code == 300) {
                    showToast('warning', response.message);
                    return;
                }

                // Update counts
                if (isOfferReject) {
                    noCount -= 1;
                    $('#v-pills-offer-tab').text("New Offer (" + noCount + ")");
                    $('#v-pills-offer-tab-mobile').text("New Offer (" + noCount + ")");
                }

                if (isReofferReject) {
                    yesCount -= 1;
                    $('#v-pills-offer-tab2').text("Reoffer (" + yesCount + ")");
                    $('#v-pills-reoffer-tab-mobile').text("Reoffer (" + yesCount + ")");
                }

                // Update combined offer list total
                var totalCount = noCount + yesCount;
                $('#v-pills-offer-dropdown-toggle .countSpan').text(totalCount);

                // Remove row/card from DOM
                $form.closest('tr, .card').fadeOut(400, function () { $(this).remove(); });

                // Redirect only when all counts are 0
                if (noCount <= 0 && yesCount <= 0) {
                    redirectToHomepage();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                Swal.fire({
                    title: "Error",
                    text: xhr.responseText || 'Internal Server Error',
                    icon: "error"
                });
            }
        });
    });
});



$(document).ready(function () {
 

    console.log("Initial count for Reoffer - Offer Reoffer:", noCount, "Reoffer Reoffer:", yesCount);

    // Detect active section when a tab is clicked
    $('.tabs1').click(function () {
        activeSection = $(this).attr("id");
        console.log("Active Section Set:", activeSection);
    });

$('.reofferButton').click(function (e) {
e.preventDefault();

var $button = $(this);
var $form = $button.closest('form');
var formData = $form.serialize();
var isOfferReoffer = $form.hasClass('offerReofferForm'); 
var isReofferReoffer = $form.hasClass('reofferReofferForm'); 

console.log("Submitting reoffer form... Offer Reoffer:", isOfferReoffer, "Reoffer Reoffer:", isReofferReoffer);

$.ajax({
url: $form.attr('action'),
type: 'POST',
data: formData,
success: function (response) {
console.log("Response received:", response);

showToast('success', response.message);

if (response.code == 300) {
showToast('warning', response.message);
return;
}

// Update counts
if (isOfferReoffer) {
noCount -= 1;
$('#v-pills-offer-tab').text("New Offer (" + noCount + ")");
$('#v-pills-offer-tab-mobile').text("New Offer (" + noCount + ")");
}

if (isReofferReoffer) {
yesCount -= 1;
$('#v-pills-offer-tab2').text("Reoffer (" + yesCount + ")");
$('#v-pills-reoffer-tab-mobile').text("Reoffer (" + yesCount + ")");
}

// Update total count
var totalCount = noCount + yesCount;
$('#v-pills-offer-dropdown-toggle .countSpan').text(totalCount);

// Remove row/card from DOM
// $form.closest('tr, .card').fadeOut(400, function () { $(this).remove(); });

// Desktop: remove table row
$form.closest('tr').fadeOut(400, function () { $(this).remove(); });

// Mobile: remove card by matching the button's product id
let reofferId = $form.closest('.modal').attr('id')?.replace('modal_', '');

if (reofferId) {
    $('.card:has([data-id="' + reofferId + '"])').fadeOut(400, function () {
        $(this).remove();
    });
}


// Close the modal
$form.closest('.modal').modal('hide');

// Redirect if all counts are 0
if (noCount <= 0 && yesCount <= 0) {
redirectToHomepage();
}
},
error: function (xhr, status, error) {
console.error("AJAX Error:", xhr.responseText);
Swal.fire({
title: "Error",
text: xhr.responseText || 'Internal Server Error',
icon: "error"
});
}
});
});



    $('.removeForm').submit(function(e) {
        e.preventDefault();

        var $form = $(this);
        var formData = $form.serialize();

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                showToast('success', response.message || 'Removed successfully');

                // Remove row/card from DOM
               $form.closest('.mobile-card, tr').fadeOut(400, function() { $(this).remove(); });

               
                var priceListCount = parseInt($('#v-pills-price-tab .countSpan').text()) || 0;
                $('#v-pills-price-tab .countSpan').text(priceListCount - 1);

               

            },
            error: function(xhr) {
                Swal.fire({
                    title: "Error",
                    text: xhr.responseText || 'Internal Server Error',
                    icon: "error"
                });
            }
        });
    });


  function triggerLastNotification() {
        $.ajax({
            url: "/send-last-reoffer-notification", // ✅ Define a new route for triggering notification
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function (response) {
                console.log("Notification sent:", response);
                setTimeout(function () {
                    window.location.href = "/homepage"; // ✅ Redirect after notification
                }, 1000);
            },
            error: function (xhr) {
                console.error("Error sending notification:", xhr.responseText);
                window.location.href = "/homepage"; // Redirect even if error occurs
            }
        });
    }
});








</script>
@endsection

<script>

function quantityMinus(inputId, totalValue, quantityValue, monthlyconsumptionValue) {
    var total = document.getElementById(totalValue).innerText;
    const input = $(`#${inputId}`);
    var value = input.val();
    if (value > 1) {
        value--;
    }
    input.val(value);

    if (quantityValue == total) {
        document.getElementsByClassName('quantity__minus').classList.add('d-none');
    } else {
        var total_value = total - quantityValue;
    }

    document.getElementById(totalValue).innerHTML = total_value;
    document.getElementById(monthlyconsumptionValue).value = total_value;
}

function quantityPlus(inputId, totalValue, quantityValue, monthlyconsumptionValue) {
    // alert(monthlyconsumptionValue)
    const input = $(`#${inputId}`);
     console.log(input);
    var value = input.val();
    
    value++;
   
    input.val(value);
    $('.quantityValue').val(value);
    var total_value = value * quantityValue;
    document.getElementById(totalValue).innerHTML = total_value;
    document.getElementById(monthlyconsumptionValue).value = total_value;

}

//     $(document).ready(function() {
//   const minus = $('.quantity__minus');
//   const plus = $('.quantity__plus');
//   const input = $('#quantity__input1');
//   minus.click(function(e) {
//     e.preventDefault();
//     var value = input.val();
//     if (value > 1) {
//       value--;
//     }
//     input.val(value);
//   });

//   plus.click(function(e) {
//     e.preventDefault();
//     var value = input.val();
//     value++;
//     input.val(value);
//   })
// });
</script>


<script>
$(function() {
    // Owl Carousel
    var owl = $("#owl-carousel");
    owl.owlCarousel({
        items: 4,
        margin: 20,
        autoplay: true,
        loop: true,
        nav: false,
    });
});
</script>
<script>
$(function() {
    // Owl Carousel
    var owl = $("#owl-carousel1");
    owl.owlCarousel({
        items: 5,
        margin: 20,
        autoplay: true,
        loop: true,
        nav: true,
    });
});
</script>

<script>
$(function() {
    // Owl Carousel
    var owl = $(".owl-carousel");
    owl.owlCarousel({
        items: 1,
        margin: 10,
        loop: true,
        nav: true
    });
});

</script>

<script>



function toggleAccordion(id) {
    var content = document.getElementById('accordion-content-' + id);
    content.style.display = content.style.display === 'none' ? 'block' : 'none';
}

function toggleAccordion1(id) {
    var content = document.getElementById('accordion-content1-' + id);
    content.style.display = content.style.display === 'none' ? 'block' : 'none';
}

function toggleAccordion2(id) {
    var content = document.getElementById('accordion-content2-' + id);
    content.style.display = content.style.display === 'none' ? 'block' : 'none';
}


  </script>



<script>
    function myFunction() {

  var checkBox = document.getElementById("myCheck");

  var text = document.getElementById("promo-i");


  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
    text.style.display = "none";
  }
}


</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tabs = document.querySelectorAll('.nav-link.tabs1');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                // Remove active class from all tabs
                tabs.forEach(function (tab) {
                    tab.classList.remove('active');
                });
                // Add active class to the clicked tab
                this.classList.add('active');
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var dropdownToggle = document.getElementById('v-pills-offer-dropdown-toggle');
        var dropdownItems = document.querySelectorAll('.dropdown-menu .nav-link.tabs1');

        // Add event listener to dropdown items
        dropdownItems.forEach(function (item) {
            item.addEventListener('click', function () {
                // Remove active class from all dropdown items
                dropdownItems.forEach(function (item) {
                    item.classList.remove('active');
                });
                // Add active class to the clicked item
                this.classList.add('active');

                // Add active class to dropdown toggle button
                dropdownToggle.classList.add('active');
            });
        });

        var tabs2Button = document.getElementById('v-pills-offer-dropdown-toggle');
        var tabs1Buttons = document.querySelectorAll('.nav-link.tabs1');

        tabs1Buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                // Check if any tabs1 button inside tabs2 dropdown is active
                var tabs1Active = document.querySelector('.dropdown-menu .nav-link.tabs1.active');
                if (tabs1Active) {
                    tabs2Button.classList.add('active');
                } else {
                    tabs2Button.classList.remove('active');
                }
            });
        });
    });


</script>
<script>
    @if($errors->any())
        showToast('error', '{{ $errors->first() }}');
    @endif

    @if(session('success'))
        showToast('success', '{{ session('success') }}');
    @endif

    function showToast(icon, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1200,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        Toast.fire({
            icon: icon,
            title: message
        });
    }



</script>


<script>
    function toggleCouponList() {
        var couponList = document.getElementById("couponList");
        var caretIcon = document.getElementById("caretIcon");

        if (couponList.style.display === "none") {
            couponList.style.display = "grid";
            caretIcon.classList.remove("fa-caret-down");
            caretIcon.classList.add("fa-caret-up");
        } else {
            couponList.style.display = "none";
            caretIcon.classList.remove("fa-caret-up");
            caretIcon.classList.add("fa-caret-down");
        }
    }

    function toggleDescription(link) {
        var description = link.nextElementSibling;
        if (description.style.display === "none") {
            description.style.display = "block";
            link.innerText = "Hide Description";
        } else {
            description.style.display = "none";
            link.innerText = "View More";
        }
    }



let currentEnquiryId = null;
let currentType = null;
let currentNewCost = null;

function openPriceConfirmModal(enquiryId, type, oldCost, newCost,productName) {
    currentEnquiryId = enquiryId;
    currentType = type;
    currentNewCost = newCost;
    currentProductName = productName;

    document.getElementById('productNameText').innerText = productName;
    document.getElementById('oldPriceText').innerText = oldCost;
    document.getElementById('newPriceText').innerText = newCost;

    $('#priceConfirmModal').modal('show');
}

function closePriceModal() {
    $('#priceConfirmModal').modal('hide');
}


function acceptPrice() {
    $.ajax({
        url: '{{ route('update.accept.cost') }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            enquiry_id: currentEnquiryId,
            type: currentType,
            new_cost: currentNewCost
        },
        success: function (response) {

            closePriceModal();

            if (response.status === 'success') {
                Swal.fire({
                    title: "Price Accepted",
                    text: response.message,
                    icon: "success",
                    confirmButtonText: "Okay",
                    allowOutsideClick: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: "Oops!",
                    text: response.message || "Something went wrong.",
                    icon: "error"
                });
            }
        },
        error: function () {
            closePriceModal();

            Swal.fire({
                title: "Error",
                text: "Something went wrong. Please try again.",
                icon: "error"
            });
        }
    });
}


function rejectPrice() {
    $.ajax({
        url: '{{ route('update.accept.cost') }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            enquiry_id: currentEnquiryId,
            action: 'reject'
        },
        success: function (response) {

            closePriceModal();

            if (response.status === 'success') {
                Swal.fire({
                    title: "Price Rejected",
                    text: response.message,
                    icon: "success",
                    confirmButtonText: "Okay",
                    allowOutsideClick: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: "Oops!",
                    text: response.message || "Something went wrong.",
                    icon: "error"
                });
            }
        },
        error: function () {
            closePriceModal();

            Swal.fire({
                title: "Error",
                text: "Something went wrong. Please try again.",
                icon: "error"
            });
        }
    });
}





function updateAcceptCost(enquiryId, type = 'product', newCost = null) {
    $.ajax({
        url: '{{ route('update.accept.cost') }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            enquiry_id: enquiryId,
            type: type,
            new_cost: newCost
        },
        success: function (response) {
            if (response.status === 'success') {
                alert(response.message);
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function () {
            alert('Something went wrong. Please try again.');
        }
    });
}


//     function updateAcceptCost(enquiryId) {
//     $.ajax({
//         url: '{{ route('update.accept.cost') }}',
//         method: 'POST',
//         data: {
//             _token: '{{ csrf_token() }}',
//             enquiry_id: enquiryId
//         },
//         success: function(response) {
//             if (response.status === 'success') {
//                 alert(response.message);
//                 location.reload(); // Reload the page to reflect changes
//             } else {
//                 alert(response.message);
//             }
//         },
//         error: function() {
//             alert('Something went wrong. Please try again.');
//         }
//     });
// }

// window.onload = function() {
//     var hash = window.location.hash;
//     var sectionId, offset;

//     if (hash === "#enquiryCart") {
//         sectionId = "enquiryCart";
//         offset = 160;
//     } else if (hash === "#priceCart") {
//         sectionId = "priceCart";
//         offset = 160;
//     } else if (hash === "#OderCart") {
//         sectionId = "OderCart";
//         offset = 160;
//     }

//     if (sectionId) {
//         var section = document.getElementById(sectionId);
//         console.log(section);

//         if (section) {
//             var rect = section.getBoundingClientRect();
//             window.scrollTo({
//                 top: rect.top + window.scrollY - offset,
//                 behavior: "smooth"
//             });
//         } else {
//             console.error(sectionId + " section not found.");
//         }
//     }
// };

// window.addEventListener('hashchange', function() {
//     location.reload();
// });

window.addEventListener("load", () => {
    document.documentElement.style.opacity = "1"; // show after activation
});


</script>

<script>
    function redirectToHomepage() {
        console.log("Redirecting to homepage...");

        Swal.fire({
            title: "Your request has been submitted!",
            text: "We'll get back to you as soon as possible.",
            icon: "success",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Okay",
            allowOutsideClick: false
        }).then(() => {
            window.location.href = '/homepage';
        });
    }

</script>

<script>
    function openOutletModal() {

    const checkoutModal = bootstrap.Modal.getInstance(
        document.getElementById('checkout')
    );

    if (checkoutModal) checkoutModal.hide();

    setTimeout(() => {
        const outletModal = new bootstrap.Modal(
            document.getElementById('locationModal')
        );
        outletModal.show();
    }, 400);
}





document.addEventListener("DOMContentLoaded", function () {

    $(document).on('click', '#saveOutletBtn', function () {

        let btn = $(this);
        btn.prop('disabled', true);

        let formData = {
            name: $('#o_name').val(),
            outlet_name: $('#o_outlet_name').val(),
            mobile_number: $('#o_mobile').val(),
            email: $('#o_email').val(),
            location: $('#o_location').val(),
            pincode: $('#o_pincode').val()
        };

        $.ajax({
            type: 'POST',
            url: '/outlet/store',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

             beforeSend: function () {
                $('#loader').show();
            },

            success: function (data) {

                $('#outletForm')[0].reset();
                $('#o_messageBox').html('');

                let outletModal = bootstrap.Modal.getInstance(
                    document.getElementById('locationModal')
                );
                if (outletModal) outletModal.hide();

                Swal.fire({
                    title: "Success!",
                    text: "Your outlet has been added.",
                    icon: "success",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = data.redirect_url;
                    }
                });
            },

            error: function (xhr) {

                let response = xhr.responseJSON;
                let html = '';

                if (response?.error) {
                    html = `<div class="alert alert-danger">${response.error}</div>`;
                } else if (response?.errors) {
                    html = '<div class="alert alert-danger"><ul>';
                    Object.values(response.errors).forEach(err => {
                        html += `<li>${err[0]}</li>`;
                    });
                    html += '</ul></div>';
                } else {
                    html = `<div class="alert alert-danger">Something went wrong</div>`;
                }

                $('#o_messageBox').html(html);
            },

            complete: function () {
                $('#loader').hide();
                btn.prop('disabled', false);
            }
        });

    });

});


</script>

</body>

</html>
