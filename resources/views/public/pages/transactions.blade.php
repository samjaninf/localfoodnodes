@extends('public.layout-page', [
    'header' => 'Transaktioner',
    'subHeader' => 'Vår transparanta ekonomi',
    'image' => '/images/money-ladder.jpg'
])

@section('title', trans('public/pages/economy.transactions'))

@section('page-content')
    <div class="container top-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body body-text">
                        <div id="transactions">
                            <transactions-list></transactions-list>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var economyTrans = <?php echo json_encode(trans('public/economy')); ?>;
    </script>
    <script src="{{ mix('/js/transactions.js') }}"></script>
@endsection
