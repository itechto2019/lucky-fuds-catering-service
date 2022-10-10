@extends('index')
@section('inventory_stocks')
<div class="for-inventory-stocks">
    <div class="for-page-title">
        <h1>Supply / Items</h1>
    </div>
    <div style="padding: 10px">
        <div style="width:fit-content;">
            <button class="b-option active-s" id="btn-item" type="button" style="padding: 10px; border: none;font-size: 18px; cursor: pointer" onclick="switchPage()">Items</button>
            <span>/</span>
            <button class="b-option" id="btn-package" type="button" style="padding: 10px; border: none;font-size: 18px;cursor: pointer" onclick="switchPage()">Package</button>
        </div>
    </div>
    <div class="form-package" style="display: none">
        <div class="form-inputs">
            <form action="{{ route('add_package') }}" method="POST">
                <div class="input-group">
                    <h3>Add Package</h3>
                </div>
                @csrf
                <div class="input-group">
                    <input type="text" name="name" placeholder="Package name">
                </div>
                <div class="input-group textarea">
                    <textarea name="details" cols="30" rows="10" placeholder="Description"></textarea>
                </div>
                <div class="input-group">
                    <input type="number" name="price" placeholder="Price">
                </div>
                <div class="input-group">
                    <button type="submit">Submit</button>
                    <div class="input-group">
                        <button class="cancel" type="button" onclick="closeAddPackage()">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="form-pop-up" style="display: none">
        <div class="form-inputs">
            <form action="{{ route('add_supply') }}" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <h3>Add supply</h3>
                </div>
                @csrf
                <div class="input-group">
                    <input type="text" name="item" placeholder="Item">
                </div>
                <div class="input-group">
                    <input type="number" name="quantity" placeholder="Quantity">
                </div>
                <div class="input-group">
                    <input type="number" name="price" placeholder="Price">
                </div>
                <div class="input-group" id="file-image">
                    <div class="img">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>
                    <input type="file" name="image" style="border: none">
                </div>
                <div class="input-group">
                    <button type="submit">Submit</button>
                    <div class="input-group">
                        <button class="cancel" onclick="closeAddForm()" type="button">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="table-reservation" id="table-supply">
        <div class="input-group" style="width:fit-content" onclick="openAddItem()">
            <button type="button">Add Item</button>
        </div>
        <div class="table-form">
            @if(!$supplies->isEmpty())
                
                <table>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($supplies as $supply)
                        <tr>
                            <td>{{ $supply->id }}</td>
                            <td><img src="{{ asset("stocks") }}/{{ $supply->image }}" width="50" height="50"></td>
                            <td>{{ $supply->item }}</td>
                            <td>{{ $supply->quantity }}</td>
                            <td>₱{{ $supply->price }}</td>
                            <td>
                                <div class="action-form">
                                    <div class="action-button">
                                        <button type="button" onclick="editSupply({{ $supply->id}})" class="action-print">
                                            Edit
                                        </button>
                                        <div class="form-pop-up-update" id="edit-{{ $supply->id }}"  style="display: none" >
                                            <div class="form-inputs">
                                                <form action="{{ route('to_update', $supply->id) }}" method="POST" enctype="multipart/form-data">
                                                    <div class="input-group">
                                                        <h3>Edit supply</h3>
                                                    </div>
                                                    @csrf
                                                    @method('put')
                                                    <div class="input-group">
                                                        <input type="text" name="item" placeholder="Item" value="{{ $supply->item }}">
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="number" name="quantity" placeholder="Quantity" value="{{ $supply->quantity }}">
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="number" name="price" placeholder="Price" value="{{ $supply->price }}">
                                                    </div>
                                                    <div class="input-group" id="file-image">
                                                        <div class="img">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                                            </svg>
                                                        </div>
                                                        <input type="file" name="image" style="border: none">
                                                    </div>
                                                    <div class="input-group">
                                                        <button type="submit" style="width: auto">Update</button>
                                                        <div class="input-group">
                                                            <button class="cancel" 
                                                            style="
                                                                border: none;
                                                                border-radius: 4px;
                                                                background-color: #9D9D9D;
                                                                cursor: pointer;
                                                                padding: 10px;
                                                                font-size: 17px;" 
                                                            onclick="closeEditSupply({{ $supply->id }})" type="button">Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="action-button">
                                        <button class="action-print" onclick="rentSupply({{ $supply->id}})">
                                            To Rent
                                        </button>
                                        <div class="form-pop-up-rent" id="rent-{{ $supply->id }}"  style="display: none" >
                                            <div class="form-inputs">
                                                <form action="{{ route('to_rent', $supply->id) }}" method="POST" enctype="multipart/form-data">
                                                    <div class="input-group">
                                                        <h3>Rent item</h3>
                                                    </div>
                                                    @csrf
                                                    @method('put')
                                                    <span style="padding: 10px">Enter quantity to be rent</span>
                                                    <div class="input-group">
                                                        <input type="number" name="quantity" placeholder="Quantity" value="{{ $supply->quantity }}">
                                                    </div>

                                                    <div class="input-group">
                                                        <button type="submit" style="width: auto">Rent</button>
                                                        <div class="input-group">
                                                            <button class="cancel" 
                                                            style="
                                                                border: none;
                                                                border-radius: 4px;
                                                                background-color: #9D9D9D;
                                                                cursor: pointer;
                                                                padding: 10px;
                                                                font-size: 17px;" 
                                                            onclick="closeRentSupply({{ $supply->id }})" type="button">Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="action-button">
                                        <form action="{{ route('delete_supply', $supply->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="action-print">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div style="padding: 10px">
                    <h3>No supplies are available</h3>
                </div>
            @endif
        </div>
    </div>
    <div class="packages" id="table-package" style="display: none">
        <div class="input-group" style="width:fit-content" onclick="openAddPackage()">
            <button type="button">Add Package</button>
        </div>
        <div class="packages-content">
            @if (!$packages->isEmpty())
                <div class="package-h3">
                    <h3>Packages</h3>
                </div>
                <div class="packages-list">
                    @foreach ($packages as $key => $pkg)
                    <div class="package">
                        <div class="input-group">
                            <h3>Package {{ $key + 1 }}</h3>
                        </div>
                        <div class="package-form">
                            <div style="padding: 0px 10px; font-size: 16px; font-weight: bold">Package Details</div>
                            <p>- {{ $pkg->details }}</p>
                            <div style="padding: 0px 10px; font-size: 16px; font-weight: bold">Rental Package price</div>
                            <p>₱ {{ (double)$pkg->price }}</p>
                        </div>
                    </div>   
                    @endforeach
                </div>
            @else
                <div style="padding: 10px">
                    <h3>No packages are available</h3>
                </div>
            @endif
        </div>
        
    </div>
</div>
@endsection
<script>
    var switchToggle = false
    function switchPage() {
        if(switchToggle) {
            $('#table-package').hide();
            $('#table-supply').show()
            $("#btn-item").addClass("active-s");
            $("#btn-package").removeClass("active-s");

            switchToggle = !switchToggle
        }else {
            $('#table-package').show();
            $('#table-supply').hide()
            $("#btn-item").removeClass("active-s");
            $("#btn-package").addClass("active-s");
            switchToggle = !switchToggle
        }

    }
    function openAddItem() {
        $('.form-pop-up').show();
    }
    function closeAddForm(){
        $('.form-pop-up').hide();
    }
    function openAddPackage() {
        $('.form-package').show();
    }
    function closeAddPackage(){
        $('.form-package').hide();
    }
    
    // update
    function editSupply(id) {
        $(`#edit-${id}`).show()
    }
    function closeEditSupply(id) {
        $(`#edit-${id}`).hide()
    }

    // rent
    function rentSupply(id) {
        $(`#rent-${id}`).show()
    }
    function closeRentSupply(id) {
        $(`#rent-${id}`).hide()
    }

</script>