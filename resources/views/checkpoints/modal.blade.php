<div class="ui medium modal">
    <div class="header">Cari Manifest</div>
    <div class="content">
        <form class="ui form" method="POST">
            <div id="dimmer-modal" class="ui dimmer">
                <div class="ui text loader">Loading</div>
            </div>
            <div id="vendor-input" class="ui field">
                <label>Kode Vendor</label>
                <input type="text" value="MASTER" id="vendor-code" placeholder="Kode Vendor">
            </div>
            <div id="manifest-input" class="ui field">
                <label>Kode Manifest</label>
                <input type="text" id="manifest-code" value="{{ $manifestCode }}" placeholder="Kode Manifest">
            </div>
        </form>
    </div>
    <div class="actions">
        <button id="manifest-search-button" class="ui green labeled icon button" type="button">
            <i class="search alternate icon"></i>
            Cari Manifest
        </button>
    </div>
</div>
