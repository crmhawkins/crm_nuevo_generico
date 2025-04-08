@extends('layouts.appTpv')

@section('titulo', 'Dashboard TPV')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/css/Tpv.css')}}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/virtual-keyboard/1.30.4/css/keyboard.min.css" integrity="sha512-4gBrVbGpT6dYENLd9Q9RsBODj4nc6/PxILsj4wFA0jwP046R76Q3B8fSR2Orzds16LoiWdC1m0ITFVaNVfQ8+A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/black-tie/theme.min.css" integrity="sha512-3XFNUWwXWmbYEhNdBem3FxT5yfJ3hzM/1sMA1m7VO9LfOAO2ctBYnPDoJYusvdrFdIglFYVLYmwm4orqrz1PEg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .navbar {
        margin: 0 !important;
        border-radius: 0px !important;
    }
    .sidebar-wrapper{
        margin: 0 !important;
        border-radius: 0px !important;
        height: 100vh;
    }
    #main{
        margin-left: 18rem ;
    }
    #mesas-container{
        height: 100%;
        width: 100%;
        background-color: white;
        border-radius: 8px; /* Bordes redondeados */

    }
    .mesa {
        width: 5vh; /* Tamaño del cuadrado */
        height: 5vh; /* Tamaño del cuadrado */
        background-color: #4CAF50; /* Color de fondo */
        color: white; /* Color del texto */
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px; /* Bordes redondeados */
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombra para un efecto 3D */
        font-weight: bold; /* Negrita para el texto */
        border: 2px solid #388E3C; /* Borde más oscuro para mejor definición */
        font-size: 2vh;
        transform: translate(30%, 48%);
        cursor:pointer;
    }

    .mesa:hover{
        color: white;
    }

    .ui-keyboard-button {
        font-size: 1.5em !important ; /* Aumentar el tamaño de la fuente de las teclas */
    }

</style>
@endsection

@section('content')
<div class="page-heading card" style="box-shadow: none !important;" >
    <div class="tpv-container">
        <div class="cuentas gris">
            <div id="mesas-container" data-url="{{ url('tpv/mesa/') }}">

            </div>
        </div>

        <div class="barras gris">
            <div id="cuentas-container" data-url="{{ url('tpv/edit/') }}">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Categorias -->
        <div class="categorias gris d-flex">
            <div class="d-flex flex-wrap" style="overflow-y: auto;">
                @foreach ($categories as $category)
                <div class="col-6 col-md-4">
                    <div class="card category-card border-0 shadow-sm text-center mx-1 mb-1" data-category-id="{{$category->id }}">
                        <div class="card-body  text-center p-0">
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="card-img-top rounded">
                            <h6 class="card-title text-primary my-1">{{ $category->name }}</h6>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Productos -->
        <div class="products gris d-flex">
            <div class="d-flex flex-wrap" style="overflow-y: auto;">
                @foreach ($products as $product)
                <div class="col-6 product-card col-md-2"style="display:none;" data-category-id="{{ $product->category_id }}"  data-product-id="{{ $product->id }}" data-product-price="{{ $product->price }}">
                    <div class="card border-0 shadow-sm mx-1 mb-1" >
                        <div class="card-body text-center p-0">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="card-img-top rounded">
                            <h6 class="card-title my-1">{{ $product->name }}</h6>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Cuenta -->
        <div class="cart">
            @if (isset($order))
            <div class="cart-header px-3 py-2 mb-1 bg-light">
                <h5 class="mb-0">
                    @if($order->mesa)
                     Mesa: {{ $order->mesa->nombre }}
                    @else
                    <div class="form-group d-flex align-items-center" style="gap: 10px;">
                        <label class="form-label mb-0" for="Cuenta" style="white-space: nowrap;">Cuenta:</label>
                        <input autocomplete="off"  type="text" class="form-control form-control-sm py-0 keyboard-full" id="mesa-nombre" value="{{ $order->nombre ?? $order->numero }}" placeholder="Ingrese nombre de la mesa">
                    </div>
                    @endif
                </h5>
            </div>
            <div class="cart-items px-2">
                @foreach ($order->items as $item )
                <div class="d-flex justify-content-between align-items-center" data-item-id="{{ $item->id }}">
                    <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                    <span>{{ $item->price * $item->quantity }}€</span>
                </div>
                @endforeach
            </div>
            <div class="cart-total">Total: {{$order->total ?? '0.00' }}€</div>
            @endif
        </div>

        <!-- Panel Numérico -->
        <div class="numpad card border-0 shadow-sm">
            <div class="card-body gris d-grid gap-2" style="grid-template-columns: repeat(4, 1fr);">
                <div class="display-cantidad">
                </div>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-light">7</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-light">8</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-light">9</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-light">4</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-light">5</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-light">6</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-light">1</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-light">2</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-light">3</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-light">0</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-light">,</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-warning" style="grid-row: 3 / 5; grid-column: 4 / 4;">Un</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-success" style="grid-row: 2 / 3; grid-column: 4 / 4;">€</button>
                <button {{!$caja ? 'disabled' : '' }} class="btn btn-danger" style="grid-row: 5 / 6; grid-column: 3 / 5;">C</button>

            </div>
        </div>
        <div class="botones card border-0 shadow-sm">
            <div class="card-body gris d-flex flex-column gap-2">
                @if (isset($order))
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal" {{!$caja ? 'disabled' : '' }}>Cobro<br><small>COBRAR CUENTA</small></button>
                <button class="btn btn-secondary" onclick="printTicket()" {{!$caja ? 'disabled' : '' }}>Pre<br><small>PRETICKET</small></button>
                <a href="{{route('dashboard')}}" class="btn btn-success" {{!$caja ? 'disabled' : '' }}>N<br><small>NUEVA CUENTA</small></a>
                <a href="{{route('tpv.mapa')}}" class="btn btn-info" {{!$caja ? 'disabled' : '' }}>M<br><small>MAPA</small></a>
                <button class="btn btn-danger delete" data-id="{{ $order->id }}" {{!$caja ? 'disabled' : '' }}>X<br><small>BORRAR TODO</small></button>
                <button class="btn btn-warning" id="delete-selected" {{!$caja ? 'disabled' : '' }}>BL<br><small>BORRAR LINEA</small></button>
                @endif
                @if ($caja)
                <button class="btn btn-dark" id="cierre">CIERRE<br><small>CIERRE DE CAJA</small></button>
                @else
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#aperturaModal">APERTURA<br><small>APERTURA DE CAJA</small></button>
                @endif
            </div>
        </div>
    </div>
    <!-- Modal para cobro -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Cobro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Total a pagar: <strong id="totalToPay">0.00€</strong></p>
                    <p>Cantidad recibida:</p>
                    <div class="d-flex mb-3">
                        <input autocomplete="off"  type="text" id="amountReceived" class="form-control text-center" readonly>
                        <button class="btn btn-danger ms-2" id="clearAmount">C</button>
                    </div>
                    <p>Cambio: <strong id="changeAmount">0.00€</strong></p>
                    <!-- Panel numérico -->
                    <div class="d-grid gap-2" style="grid-template-columns: repeat(3, 1fr);">
                        <button class="btn btn-light numpad-btn">7</button>
                        <button class="btn btn-light numpad-btn">8</button>
                        <button class="btn btn-light numpad-btn">9</button>
                        <button class="btn btn-light numpad-btn">4</button>
                        <button class="btn btn-light numpad-btn">5</button>
                        <button class="btn btn-light numpad-btn">6</button>
                        <button class="btn btn-light numpad-btn">1</button>
                        <button class="btn btn-light numpad-btn">2</button>
                        <button class="btn btn-light numpad-btn">3</button>
                        <button class="btn btn-light numpad-btn">0</button>
                        <button class="btn btn-light numpad-btn">,</button>
                        <button class="btn btn-warning" id="backspace">←</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmPayment">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="cierreModal" tabindex="-1" aria-labelledby="cierreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cierreModalLabel">Apertura de Caja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('tpv.cerrarCaja')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-2 form-group">
                            <label class="form-label" for="cierre">Cierre</label>
                            <input autocomplete="off"  class="form-control keyboard-init" value="{{old('cierre')}}" name="cierre" type="text" id="cierrecaja">
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2 form-group">
                            <label class="form-label" for="cambio">Cambio</label>
                            <input autocomplete="off"  class="form-control keyboard-init ui-widget-content ui-corner-all" value="{{old('cambio')}}" name="cambio" type="text" id="cambio">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="aperturaModal" tabindex="-1" aria-labelledby="aperturaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="aperturaModalLabel">Apertura de Caja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('tpv.abrirCaja')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-2 form-group">
                            <label class="form-label" for="apertura">Cambio en caja</label>
                            <input autocomplete="off"  class="form-control keyboard-init ui-widget-content ui-corner-all" name="apertura" value="{{old('apertura')}}" type="text" id="apertura">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Carga jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/caret/1.3.7/jquery.caret.min.js" integrity="sha512-DR6H+EMq4MRv9T/QJGF4zuiGrnzTM2gRVeLb5DOll25f3Nfx3dQp/NlneENuIwRHngZ3eN6w9jqqybT3Lwq+4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/jquery-ui.min.js" integrity="sha512-MSOo1aY+3pXCOCdGAYoBZ6YGI0aragoQsg1mKKBHXCYPIWxamwOE7Drh+N5CPgGI5SA9IEKJiPjdfqWFWmZtRA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- CSS y JS del plugin -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/virtual-keyboard/1.30.4/languages/es.min.js" integrity="sha512-ITqWMeHU/52KlwUxKod9gI0cs8PhhCbigIsr2XBLobFR+80Cw9qZqgice6ewwsUqL2AuN5v7WJBO5XoW6C0dUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/virtual-keyboard/1.30.4/js/jquery.keyboard.min.js" integrity="sha512-bnF44HiiJPFa0Kzr+pxQktiT6MnrdSWn/jSkZcJY9Lsw2TRIWZfkI1zo6uNfKEvK0QK2AsJuvf7AIxUmCDOOmw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/virtual-keyboard/1.30.4/css/keyboard-dark.min.css" integrity="sha512-zBvvUVHqnKccyZ+Wkp4tJEuVHqCjWd0JXNhFNfWGkXCYL4aL8gnnFqcH3NwyitEoOjyEDFtfWlvnK4qGigda/A==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}


@include('partials.toast')
<script>

    $(document).ready(() => {


        $('.keyboard-init').keyboard({
            layout: 'custom',
            customLayout: {
                'default': [
                    '7 8 9',
                    '4 5 6',
                    '1 2 3',
                    '0 . {b}',
                ],
            },
            display: {
                '{b}': '←',
            },
            restrictInput: true,
            preventPaste: true,
            autoAccept: true,
            usePreview: false,
        });

        $('.keyboard-full').keyboard({
            restrictInput: true,
            preventPaste: true,
            autoAccept: true,
            usePreview: false,
        });

        $('#mesa-nombre').blur(function() {
            cambioNombre();
        });

    });
    function cambioNombre(){
        var nombre = document.getElementById('mesa-nombre').value;
        var id = @json(isset($order) ? $order->id : null);
        $.ajax({
            type: "POST",
            url: '/tpv/setName',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: {
                'nombre': nombre,
                'id': id,
            },
            dataType: "json",
            success: function(data) {
                Toast.fire({
                    icon: "success",
                    title: data.mensaje
                });
            },
            error: function(data) {
                Toast.fire({
                    icon: "error",
                    title: data.responseJSON.mensaje
                });
            }
        });
    }
    $("#sidebar").css("display", "none");
    $("#main").css("margin-left", "0px");
    document.querySelectorAll('.category-card').forEach(categoryCard => {
        categoryCard.addEventListener('click', () => {
            const categoryId = categoryCard.getAttribute('data-category-id');
            document.querySelectorAll('.product-card').forEach(productCard => {
                productCard.style.display = productCard.getAttribute('data-category-id') === categoryId ? 'block' : 'none';
            });
        });
    });
    let cantidadSeleccionada = '';
    let cambioprecio = false; // Establece esto según el contexto

    document.querySelectorAll('.numpad button').forEach(button => {
        button.addEventListener('click', () => {
            const valor = button.textContent;
            if (!isNaN(valor) || valor === ',') { // Si es un número
                if (valor === ',' && !cantidadSeleccionada.includes('.')) {
                    cantidadSeleccionada += '.';
                } else if (valor !== ',') {
                    cantidadSeleccionada += valor;
                }
            } else if (valor === 'C') { // Limpiar el numpad
                cantidadSeleccionada = '';
            }else if (valor === '€') {
                cambioprecio = true;
            } else if (valor === 'Un') { // Eliminar el último carácter
                cantidadSeleccionada = cantidadSeleccionada.slice(0, -1);
            }
            document.querySelector('.display-cantidad').textContent = `${cantidadSeleccionada}` + (cambioprecio ? '€' : '');
        });
    });


    document.getElementById('delete-selected').addEventListener('click', () => {
        const selected = document.querySelector('.cart-items .selected');
        const itemId = selected.getAttribute('data-item-id');

        fetch('/tpv/remove-item', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ item_id: itemId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.order) {
                selected.remove();
                updateCartTotal();
            }
        })
        .catch(error => console.error('Error al agregar ítem:', error));
    });
    document.getElementById('cierre').addEventListener('click', () => {
        botonCierre();
    });

    function botonCierre(){
        Swal.fire({
            icon: "warning",
            title: "¿Estas seguro que quieres cerrar la caja?",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Cerrar Caja",
            cancelButtonText: "Cancelar",
        }).then((result) => {

            if (result.isConfirmed) {
                fetch('/tpv/data', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                })
                .then(response => response.json())
                .then(data => {
                    const tieneMesasAbiertas = data.mesas && data.mesas.some(mesa => mesa.has_open_order === true);
                    if( tieneMesasAbiertas || (data.ordenes && data.ordenes.length > 0)){
                        console.log(data);
                        Swal.fire({
                            icon: "warning",
                            title: "Hay mesas o cuentas abiertas",
                            showDenyButton: false,
                            showCancelButton: true,
                            confirmButtonText: "Cerrar Caja",
                            cancelButtonText: "Cancelar",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#cierreModal').modal('show');
                            }
                        });
                    }else{
                        $('#cierreModal').modal('show');

                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            }
        });
    }

    function printTicket() {
        let printContents = document.querySelector('.cart').innerHTML;
        let originalContents = document.body.innerHTML;

        let printWindow = window.open('', '_blank', 'width=80mm');
        printWindow.document.write('<html><head><title>Print Ticket</title>');
        printWindow.document.write('<link rel="stylesheet" href="style.css">'); // Asegúrate de incluir el estilo correcto si es necesario
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }

    document.querySelectorAll('.product-card').forEach(productCard => {
        const caja = @json($caja);
        if(!caja){
            return;
        }
        productCard.addEventListener('click', () => {
            var orderId = @json(isset($order) ? $order->id : null);

            const productId = productCard.getAttribute('data-product-id');
            var price = parseFloat(productCard.getAttribute('data-product-price'));
            var quantity = cantidadSeleccionada || 1;
            if(cambioprecio == true){
                price = parseFloat(cantidadSeleccionada).toFixed(2);
                quantity = 1;
                cambioprecio = false;
            }
            fetch('/tpv/add-item', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ order_id: orderId, product_id: productId, quantity, price })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.order) {
                        cantidadSeleccionada = '';
                        document.querySelector('.display-cantidad').textContent = `${cantidadSeleccionada}`;
                        const cartItems = document.querySelector('.cart-items');
                        cartItems.innerHTML = '';
                        (data.items).forEach(item => {
                            const cartItems = document.querySelector('.cart-items');
                            const productElement = document.createElement('div');
                            productElement.classList.add('d-flex', 'justify-content-between', 'align-items-center');
                            productElement.setAttribute('data-item-id', item.id);
                            productElement.innerHTML = `
                                <span>${item.product.name} x ${item.quantity}</span>
                                <span>${(parseFloat((item.price).replace('€', ''))* item.quantity).toFixed(2)}€</span>
                                `;
                            cartItems.appendChild(productElement);
                            selected();
                            updateCartTotal();
                        });
                    }
                })
                .catch(error => console.error('Error al agregar ítem:', error));
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        selected();
    });



    function selected(){
        const items = document.querySelectorAll('.cart-items div');
        items.forEach(item => {
            item.addEventListener('click', () => {
                if(document.querySelector('.selected')) {
                    document.querySelector('.selected').classList.remove('selected');
                }
                item.classList.add('selected');
            });
        });
    }
    function updateCartTotal() {
        const items = document.querySelectorAll('.cart-items div');
        let total = 0;
        items.forEach(item => {
            const itemPrice = parseFloat(item.children[1].textContent.replace('€', ''));
            total += itemPrice;
        });
        document.querySelector('.cart-total').textContent = `Total: ${total.toFixed(2)}€`;
    }

</script>
<script>
    $(document).ready(() => {
        $('.delete').on('click', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            botonAceptar(id);
        });
    });

    function botonAceptar(id){
        Swal.fire({
            title: "¿Estas seguro que quieres eliminar esta cuenta?",
            html: "<p>Esta acción es irreversible.</p>",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Borrar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                $.when(getDelete(id)).then(function(data, textStatus, jqXHR) {
                    if (!data.status) {
                        Toast.fire({
                            icon: "error",
                            title: data.mensaje
                        });
                    } else {
                        Toast.fire({
                            icon: "success",
                            title: data.mensaje
                        }).then(() => {
                            window.location.href = '/dashboard'
                        });
                    }
                });
            }
        });
    }

    function getDelete(id) {
        const url = '{{route("tpv.delete")}}';
        return $.ajax({
            type: "POST",
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: {
                'id': id,
            },
            dataType: "json"
        });
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const totalToPayElement = document.getElementById('totalToPay');
        const amountReceivedElement = document.getElementById('amountReceived');
        const changeAmountElement = document.getElementById('changeAmount');
        const numpadButtons = document.querySelectorAll('.numpad-btn');
        const clearButton = document.getElementById('clearAmount');
        const backspaceButton = document.getElementById('backspace');
        const confirmPaymentButton = document.getElementById('confirmPayment');

        // Función para actualizar el total dinámicamente
        const updateTotal = () => {
            const totalElement = document.querySelector('.cart-total');
            const total = parseFloat(totalElement.textContent.replace('Total: ', '').replace('€', '')) || 0.00;
            totalToPayElement.textContent = `${total.toFixed(2)}€`;
            return total;
        };

        let amountReceived = '';
        let totalToPay = updateTotal();

        const updateChange = () => {
            totalToPay = updateTotal();
            const received = parseFloat(amountReceived) || 0.00;
            const change = received - totalToPay;
            console.log(change, received, totalToPay,amountReceived);
            changeAmountElement.textContent = `${change >= 0 ? change.toFixed(2) : '0.00'}€`;
        };

        clearButton.addEventListener('click', () => {
            amountReceived = '';
            amountReceivedElement.value = '';
            updateChange();
        });

        // Botón para borrar el último carácter
        backspaceButton.addEventListener('click', () => {
            amountReceived = amountReceived.slice(0, -1);
            amountReceivedElement.value = amountReceived;
            updateChange();
        });
        // Panel numérico
        numpadButtons.forEach(button => {
            button.addEventListener('click', () => {
                const value = button.textContent;
                if (value === ',' && !amountReceived.includes('.')) {
                    amountReceived += '.';
                } else if (!isNaN(value)) {
                    amountReceived += value;
                }
                amountReceivedElement.value = amountReceived;
                updateChange();
            });
        });

        confirmPaymentButton.addEventListener('click', () => {
            if (parseFloat(amountReceived) >= totalToPay) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pago realizado',
                    text: `Cambio: ${(parseFloat(amountReceived) - totalToPay).toFixed(2)}€`,
                }).then(() => {
                    const id = @json(isset($order) ? $order->id : null);

                    fetch('/tpv/checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ order_id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            Toast.fire({
                                icon: "success",
                                title: 'Pago realizado'
                            }).then(() => {
                                window.location.href = '/dashboard'
                            });
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al realizar el Pago',
                                text: data.message,
                            });
                        }
                    })
                    .catch(error => console.error('Error al agregar ítem:', error));
                });

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Pago insuficiente',
                    text: 'El monto recibido es menor al total.',
                });
            }
        });

        document.addEventListener('click', updateTotal);
    });
    document.addEventListener('hidden.bs.modal', (event) => {
        amountReceived = '';
        const triggerButton = document.querySelector('[data-bs-target="#paymentModal"]');
        if (triggerButton) {
            triggerButton.focus();
        }
    });
</script>
<script>
        $(document).ready(() => {
        getData();
        });
    function getData(){
        fetch('/tpv/data', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
        .then(response => response.json())
        .then(data => {
            loadMesas(data.mesas);
            loadOrders(data.ordenes);

        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    function loadOrders(orders) {
        const $tbody = $('#cuentas-container tbody').empty();
        const rows = [];

        orders.forEach(order => {
            const nombreDisplay = order.nombre || `Número: ${order.numero}`;
            const totalDisplay = `${parseFloat(order.total).toFixed(2)}€`;

            const row = `<tr class="clickable-row" data-href="/tpv/edit/${order.id}">
                            <td>${nombreDisplay}</td>
                            <td>${totalDisplay}</td>
                        </tr>`;
            rows.push(row); // Collect all rows in an array
        });
        $tbody.append(rows.join('')); // Append all rows at once using join to convert array to string
        const rowsclick = document.querySelectorAll("tr.clickable-row");

        rowsclick.forEach(row => {
            row.addEventListener("click", () => {
                const href = row.dataset.href;
                if (href) {
                    window.location.href = href;
                }
            });
        });
    }
    function loadMesas(mesas) {
        const $container = $('#mesas-container');
        const baseUrl = $container.data('url');
        const width = $container.width();
        const height = $container.height();

        const fragment = document.createDocumentFragment(); // Use a document fragment to avoid multiple reflows

        mesas.forEach(mesa => {
            const left = mesa.posicion_x * width / 100;
            const top = mesa.posicion_y * height / 100;
            const color = mesa.has_open_order ? 'blue' : '#4CAF50';

            const anchor = $('<a>').addClass('mesa')
                                .css({ position: 'absolute', left: `${left}px`, top: `${top}px`, 'background-color': color })
                                .attr('href', `${baseUrl}/${mesa.id}`)
                                .text(mesa.nombre);

            fragment.appendChild(anchor[0]); // Append to the fragment
        });

        $container.empty().append(fragment); // Append all at once
    }
</script>
@endsection
