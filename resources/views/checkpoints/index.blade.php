<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="token" content="{{ csrf_token() }}">
    <title>Document</title>
    @include('checkpoints/style')
    <style>
        .none {
            display: none;
        }
    </style>
</head>

<body>

    <div class="ui container">
        <div class="navbar-bg"></div>
        <div class="ui stacked segment">
            <div class="ui tabular menu">
                <div class="active item" data-tab="tab-checkpoint">Checkpoint Vendors</div>
                <div class="item" data-tab="tab-manifest-checkpoint">Daftar Manifest Checkpoint</div>
            </div>
            <div class="ui active tab" data-tab="tab-checkpoint">
                @include('checkpoints/tabcheckpoint')
            </div>
            <div class="ui tab" data-tab="tab-manifest-checkpoint">
                @include('checkpoints/tablistmanifest')
            </div>
        </div>
    </div>
    @include('checkpoints/modal')
    @include('checkpoints/script')
</body>

</html>
