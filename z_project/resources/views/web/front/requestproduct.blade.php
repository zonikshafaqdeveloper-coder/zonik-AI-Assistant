@extends('web.layouts.app')

@section('content')
<!-- --------------order dropdown----------------- -->

<div class="container mt-5 mb-5">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="page-h">Request a product</h2>
            <div class="break-line mt-4"></div>
            <form action="{{ route('productrequest.store') }}" method="post">
                @csrf
                <div>
                    <p class="question">Tell us if you can’t find a product and we will add it to the shop as soon as
                        possible.</p>
                    <div class="mt-4">

                        <input type="text" name="product_name" placeholder="Enter product name" class="form-control lable lable1">
                    </div>
                </div>
                <div class="mt-5">
                    <p class="question">Tell us more about the product <span class="grey">(Optional)</span></p>
                    <div class="mt-4">
                        <input type="text" name="product_details"
                            placeholder="Add brand name, pack size and expected price.." class="form-control lable lable1">
                    </div>
                </div>
                <div class="d-flex mt-5">
                    <button type="submit" class="btn red-btn">Submit</button>
                    <button type="reset" class="btn red-btn">Dismiss</button>
                </div>
            </form>
        </div>

    </div>
</div>

<style>
    .lable1::placeholder {
        color: #828282 !important;
        font-size: 18px;
        font-weight: 600;
        letter-spacing: 0.2px;
    }

    .lable {
        width: 50% !important;
        border-radius: 5px !important;
    }


    .question {
        color: #363636;
        font-size: 18px;
        margin: 50px 0 16px;
        width: 50%;
        font-weight: 600;
    }

    @media (max-width: 767px) {
        .lable {
            width: 100% !important;
            border-radius: 5px !important;
        }

        .question {
            color: #363636;
            font-size: 16px;
            margin: 50px 0 16px;
            width: 100%;
            font-weight: 500;
        }
    }


    .page-h {
        margin-top: 20px;
        color: #4f4f4f;
        font-size: 30px;
    }

    .break-line {
        font-size: 0;
        width: 100%;
        border-top: 1px solid #ebeb;
    }
</style>
@endsection

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
</script>
