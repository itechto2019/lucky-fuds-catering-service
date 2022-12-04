@extends('index')
@section('inventory_stocks')
<div class="for-inventory-stocks">
    <div class="for-page-title">
        <h1>Supply / Items</h1>
    </div>
    <div class="error-message">
        @foreach ($errors->all() as $error)
        <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #FFFFFF">{{$error}}</div>
        @endforeach
        @if(session()->has('message'))
            <div style="padding: 15px; margin:5px; background-color: #38E54D; color: #1a1a1a1">
                {{ session()->get('message') }}
            </div>
        @endif
    </div>
    <div style="padding: 10px">
        <div style="width:fit-content;">
            <button class="b-option active-s" id="btn-item" type="button"
                style="padding: 10px; border: none;font-size: 18px; cursor: pointer"
                onclick="switchPage()">Items</button>
            <span>/</span>
            <button class="b-option" id="btn-package" type="button"
                style="padding: 10px; border: none;font-size: 18px;cursor: pointer"
                onclick="switchPage()">Package</button>
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
                    <input type="text" name="name" placeholder="Package name" value="{{ old('name') }}">
                </div>
                <div style="padding: 10px;color: red;">
                    @error('name')
                    <small>{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-group textarea">
                    <textarea name="details" cols="30" rows="10" placeholder="Description"
                        value="{{ old('details') }}"></textarea>
                </div>
                <div style="padding: 10px;color: red;">
                    @error('details')
                    <small>{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-group">
                    <input type="number" name="price" placeholder="Price" value="{{ old('price') }}">
                </div>
                <div style="padding: 10px;color: red;">
                    @error('price')
                    <small>{{ $message }}</small>
                    @enderror
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
    <div class="form-pop-up" style="display: none;z-index:1">
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
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
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
        <div class="input-search" style="display: flex;align-items:center;">
            <form action="?search=" style="width:100%;display: flex;align-items:center">
                <div class="input-group" style="width:80%;position: relative">
                    <input type="text" name="search" style="padding-left: 15px;border-style:none;border-radius: 5px;background-color: #A8CD96" placeholder="Search item" />
                    <svg style="position: absolute;right: 0;padding: 23px;width: 25px; height: 25px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <div class="input-group" onclick="openAddItem()">
                    <button type="button" style="width: 100%">Add Item</button>
                </div>
            </form>
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
                    <td><img src="{{ $supply->image }}" width="50" height="50"></td>
                    <td>{{ $supply->item }}</td>
                    <td>{{ $supply->quantity }}</td>
                    <td>₱{{ $supply->price }}</td>
                    <td>
                        <div class="action-form">
                            <div class="action-button">
                                <button type="button" onclick="editSupply({{ $supply->id}})" class="action-print">
                                    Edit
                                </button>
                                <div class="form-pop-up-update" id="edit-{{ $supply->id }}" style="display: none">
                                    <div class="form-inputs">
                                        <form action="{{ route('to_update', $supply->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            <div class="input-group">
                                                <h3>Edit supply</h3>
                                            </div>
                                            @csrf
                                            @method('put')
                                            <div class="input-group">
                                                <input type="text" name="item" placeholder="Item"
                                                    value="{{ $supply->item }}">
                                            </div>
                                            <div class="input-group">
                                                <input type="number" name="quantity" placeholder="Quantity"
                                                    value="{{ $supply->quantity }}">
                                            </div>
                                            <div class="input-group">
                                                <input type="number" name="price" placeholder="Price"
                                                    value="{{ $supply->price }}">
                                            </div>
                                            <div class="input-group" id="file-image">
                                                <div class="img">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                                    </svg>
                                                </div>
                                                <input type="file" name="image" style="border: none">
                                            </div>
                                            <div class="input-group">
                                                <button type="submit" style="width: auto">Update</button>
                                                <div class="input-group">
                                                    <button class="cancel" style="
                                                                border: none;
                                                                border-radius: 4px;
                                                                background-color: #9D9D9D;
                                                                cursor: pointer;
                                                                padding: 10px;
                                                                font-size: 17px;"
                                                        onclick="closeEditSupply({{ $supply->id }})"
                                                        type="button">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="action-button">
                                <button class="action-print"
                                    onclick="rentSupply({{ $supply->id}}, {{ $supply->quantity }})">
                                    To Rent
                                </button>
                                <div class="form-pop-up-rent" id="rent-{{ $supply->id }}" style="display: none">
                                    <div class="form-inputs">
                                        <form action="{{ route('to_rent', $supply->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            <div class="input-group">
                                                <h3>Rent item</h3>
                                            </div>
                                            @csrf
                                            @method('put')
                                            <span style="padding: 10px">Enter quantity to be rent</span>
                                            <div class="input-group">
                                                <input type="number" name="quantity" placeholder="Quantity"
                                                    id="quantity-{{ $supply->id }}" value="{{ $supply->quantity }}"
                                                    onchange="onChangeRentSupply(event, {{ $supply->id }}, {{ $supply->quantity }})">
                                            </div>
                                            <div style="padding: 10px">
                                                <span style="color:#FF1E1E;" id="max-quantity-{{ $supply->id }}"></span>
                                            </div>

                                            <div class="input-group">
                                                <button type="submit" style="width: auto">Rent</button>
                                                <div class="input-group">
                                                    <button class="cancel" style="
                                                                border: none;
                                                                border-radius: 4px;
                                                                background-color: #9D9D9D;
                                                                cursor: pointer;
                                                                padding: 10px;
                                                                font-size: 17px;"
                                                        onclick="closeRentSupply({{ $supply->id }})"
                                                        type="button">Cancel</button>
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
                        <div style="word-wrap:break-word;padding: 5px 10px">{{ $pkg->details }}</div>
                        <div style="padding: 0px 10px; font-size: 16px; font-weight: bold">Rental Package price</div>
                        <p>₱ {{ (double)$pkg->price }}</p>
                    </div>
                    <div class="input-group" style="display: grid; grid-template-columns: auto auto; column-gap: 5px">
                        <button type="button" onclick="editPackage({{ $pkg->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 35px;height: 35px;" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </button>
                        <form action="{{ route('delete_package', $pkg->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 35px;height: 35px;" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    <div class="for-form-package" style="display: none" id="edit-package-{{ $pkg->id }}">
                        <div class="form-inputs">
                            <form action="{{ route('edit_package', $pkg->id) }}" method="POST">
                                <div class="input-group">
                                    <h3>Edit Package</h3>
                                </div>
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <input type="text" name="name" placeholder="Package name" value="{{ $pkg->name }}">
                                </div>
                                <div style="padding: 10px;color: red;">
                                    @error('name')
                                    <small>{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="input-group textarea">
                                    <textarea name="details" cols="30" rows="10"
                                        placeholder="Description">{{ $pkg->details }}</textarea>
                                </div>
                                <div style="padding: 10px;color: red;">
                                    @error('details')
                                    <small>{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="input-group">
                                    <input type="number" name="price" placeholder="Price" value="{{ $pkg->price }}">
                                </div>
                                <div style="padding: 10px;color: red;">
                                    @error('price')
                                    <small>{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="input-group">
                                    <button type="submit">Submit</button>
                                    <div class="input-group">
                                        <button class="cancel" type="button"
                                            onclick="closeEditPackage({{ $pkg->id }})">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
    function onChangeRentSupply(event, id, q) {
        if(event.target.value > q) {
            $(`#max-quantity-${id}`).show()
            $('#quantity-' + id).val(q)
            $(`#max-quantity-${id}`).text("You can't rent above the quantity")
        }else {
            $(`#max-quantity-${id}`).hide()
        }
        if(event.target.value === 0) {
            $('#quantity-' + id).val(1)
        }
    }
    function closeRentSupply(id) {
        $(`#rent-${id}`).hide()
    }
    function editPackage(id){
        $('#edit-package-'+id).show();
    }
    function closeEditPackage(id) {
        $('#edit-package-' + id).hide()
    }

</script>