{{-- Master Include  --}}
@extends('admin.layout.master')

{{-- Define Site Title  --}}
@section('title', 'Mail Box Imap')

{{-- Content Extends  --}}
@section('content')



    <x-summary>
        <div class="row connectedSortable mb-2">
            {{--  payment Report  --}}


            @can('imap management')
                <x-dashboard.link_card column="col-lg-3 col-6" bg="bg-primary" items="mail_box"  where="" title="Total Mail"
                    icon="far fa-payment" link="#" sort="sort_3" />
            @endcan

            @can('smtp read')
                <x-dashboard.link_card column="col-lg-3 col-6" bg="bg-success"
                    title="Mail Configuration" icon="far fa-payment" link="{{ route('admin.mail.index') }}" sort="sort_3" />
            @endcan


        </div>
    </x-summary>


    <!-- Include Livewire component -->
    <livewire:imap-inbox />



    <style>
       .card-body  li.nav-item.active {
            background: #1ee5c5;
        }

        .compose_box {
            position: fixed;
            z-index : 99999;

            right: 0;
            width: 70%;
            max-width: 1000px;
            bottom: 0;
            max-height: 80%;
            min-height: 400px;
        }

        button.fixed_write_message {
            position: fixed;
            right: 24px;
            bottom: 70px;
            z-index: 1046;
            border: none;
            background: #fff;
            border: 1px solid gray;
            border-radius: 50%;
            padding: 15px;
            /* aspect-ratio: 1/1; */
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #216573;
            color: white;
        }



        .compose_box .card-body {
            overflow: auto;
        }

        .mailbox-read-message .row .col-md-6,
        .compose_box_data_body .row .col-md-6{
            width:100% !important;
            max-width: 100%;
            flex: 0 0 100%;
        }
    </style>

<script>

function printElement(selector) {
    const element = document.querySelector(selector);
    if (!element) {
        console.error("Element not found:", selector);
        return;
    }

    const content = element.innerHTML;
    const printWindow = window.open('', '', 'width=800,height=600');

    if (!printWindow) {
        alert("Popup blocked! Please allow popups for this site.");
        return;
    }


    printWindow.document.open();
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Print</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                }
            </style>
        </head>
        <body>
            ${content}
        </body>
        </html>
    `);
    printWindow.document.close();

    // Wait for content to load before printing
   // ✅ Delay to allow full render
    setTimeout(() => {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }, 500); // 500ms is usually safe; adjust if needed
}
</script>



@endsection

@push('js')
    @livewireScripts
@endpush
