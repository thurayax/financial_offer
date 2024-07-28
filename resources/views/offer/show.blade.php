<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض مالي</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zain:wght@200;300;400;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        body {
            font-family: 'Zain', sans-serif;
            font-weight: 400;
            direction: rtl;
            text-align: right;
            background-color: #f3f4f6;
        }
        .zain-extralight {
            font-weight: 200;
        }
        .zain-light {
            font-weight: 300;
        }
        .zain-regular {
            font-weight: 400;
        }
        .zain-bold {
            font-weight: 700;
        }
        .zain-extrabold {
            font-weight: 800;
        }
        .zain-black {
            font-weight: 900;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container" id="content">
        <div class="bg-blue-100 text-center font-bold p-4 mb-4 zain-bold">عرض مالي</div>

        <div class="info mb-6">
            <div class="mb-2 zain-regular">السادة: {{ $client->name }}</div>
            <div class="mb-2 zain-regular">التاريخ: <span id="current-date">{{ now()->format('Y-m-d') }}</span></div>
            <div class="zain-regular">عنوان العميل: {{ $client->address }}</div>
        </div>

        <div class="section p-4 rounded bg-white shadow">
            <table class="min-w-full border-collapse" id="service-table">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="border px-4 py-2 zain-bold">رقم البند</th>
                        <th class="border px-4 py-2 zain-bold">الخدمة</th>
                        <th class="border px-4 py-2 zain-bold">سعر الخدمة</th>
                        <th class="border px-4 py-2 zain-bold">الكمية</th>
                        <th class="border px-4 py-2 zain-bold">السعر الإجمالي</th>
                    </tr>
                </thead>
                <tbody id="service-table-body">
                    <tr>
                        <td class="border px-4 py-2 zain-regular">1</td>
                        <td class="border px-4 py-2 zain-regular">
                            <input type="text" class="w-full border border-gray-300 rounded p-1 zain-regular" placeholder="أدخل الخدمة">
                        </td>
                        <td class="border px-4 py-2 zain-regular">
                            <input type="number" class="w-full border border-gray-300 rounded p-1 zain-regular" placeholder="السعر" oninput="calculateTotalPrice()">
                        </td>
                        <td class="border px-4 py-2 zain-regular">
                            <div class="quantity-controls flex justify-center items-center">
                                <button type="button" class="px-2 py-1 bg-gray-200 rounded zain-regular" onclick="decreaseQuantity(this)">-</button>
                                <span class="quantity mx-2 zain-regular">1</span>
                                <button type="button" class="px-2 py-1 bg-gray-200 rounded zain-regular" onclick="increaseQuantity(this)">+</button>
                            </div>
                        </td>
                        <td class="border px-4 py-2 zain-regular">100</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-right mt-4 zain-bold">
                السعر الإجمالي: <span id="total-price">100</span>
            </div>
            <div class="flex justify-between mt-4">
                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded zain-bold" onclick="addServiceRow()">إضافة خدمة</button>
                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded zain-bold" onclick="deleteLastRow()">حذف آخر خدمة</button>
            </div>
        </div>

        <div class="section p-4 mt-6 bg-white shadow rounded">
            <div class="footer bg-blue-100 p-2 text-center zain-bold">بيان</div>
            <div class="text-right mt-4 zain-regular">
                <div class="zain-regular"> {{ $client->statement }}</div>
            </div>
        </div>

        <div class="section p-4 mt-6 bg-white shadow rounded">
            <div class="footer bg-blue-100 p-2 text-center zain-bold">سياسة الدفع</div>
            <div class="text-right mt-4 zain-regular">
                <textarea id="payment_policy" name="payment_policy" class="w-full border border-gray-300 rounded p-2 zain-regular" rows="5" oninput="updatePaymentPolicy(this)">{{ $client->payment_policy ?? "1. يتم تحويل المبلغ على حسب الدفعات التالية:\n50% كدفعة أولى\n30% كدفعة ثانية\n20% كدفعة ثالثة\n2. يتم تحويل المبلغ إلى حساب جمعية عون التقنية - البنك الأهلي - IBAN SA3310000000200000444207" }}</textarea>
            </div>
        </div>
        
        <div class="text-center mt-6">
            <a href="{{ route('offer.exportPdf', $client->id) }}" class="bg-green-500 text-white px-4 py-2 rounded zain-bold">تصدير إلى PDF</a>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var today = new Date();
            var date = today.getFullYear() + '-' + (today.getMonth() + 1).toString().padStart(2, '0') + '-' + today.getDate().toString().padStart(2, '0');
            document.getElementById('current-date').textContent = date;
            calculateTotalPrice();
        });

        function increaseQuantity(button) {
            var quantityElement = button.parentNode.querySelector('.quantity');
            var quantity = parseInt(quantityElement.textContent);
            quantity++;
            quantityElement.textContent = quantity;
            calculateTotalPrice();
        }

        function decreaseQuantity(button) {
            var quantityElement = button.parentNode.querySelector('.quantity');
            var quantity = parseInt(quantityElement.textContent);
            if (quantity > 1) {
                quantity--;
                quantityElement.textContent = quantity;
                calculateTotalPrice();
            }
        }

        function addServiceRow() {
            var tableBody = document.getElementById('service-table-body');
            var newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="border px-4 py-2 zain-regular">${tableBody.rows.length + 1}</td>
                <td class="border px-4 py-2 zain-regular">
                    <input type="text" class="w-full border border-gray-300 rounded p-1 zain-regular" placeholder="أدخل الخدمة">
                </td>
                <td class="border px-4 py-2 zain-regular">
                    <input type="number" class="w-full border border-gray-300 rounded p-1 zain-regular"  oninput="calculateTotalPrice()">
                </td>
                <td class="border px-4 py-2 zain-regular">
                    <div class="quantity-controls flex justify-center items-center">
                        <button type="button" class="px-2 py-1 bg-gray-200 rounded zain-regular" onclick="decreaseQuantity(this)">-</button>
                        <span class="quantity mx-2 zain-regular">1</span>
                        <button type="button" class="px-2 py-1 bg-gray-200 rounded zain-regular" onclick="increaseQuantity(this)">+</button>
                    </div>
                </td>
                <td class="border px-4 py-2 zain-regular">100</td>
            `;
            tableBody.appendChild(newRow);
            calculateTotalPrice();
        }

        function deleteLastRow() {
            var tableBody = document.getElementById('service-table-body');
            if (tableBody.rows.length > 1) {
                tableBody.deleteRow(tableBody.rows.length - 1);
                calculateTotalPrice();
            }
        }

        function calculateTotalPrice() {
            var tableBody = document.getElementById('service-table-body');
            var totalPrice = 0;
            for (var i = 0; i < tableBody.rows.length; i++) {
                var row = tableBody.rows[i];
                var quantity = parseInt(row.querySelector('.quantity').textContent);
                var pricePerItem = parseInt(row.cells[2].querySelector('input').value);
                var totalRowPrice = quantity * pricePerItem;
                row.cells[4].textContent = totalRowPrice;
                totalPrice += totalRowPrice;
            }
            document.getElementById('total-price').textContent = totalPrice;
        }

        function exportToPDF() {
            var { jsPDF } = window.jspdf;
            var doc = new jsPDF();
            doc.fromHTML(document.getElementById('content'), 15, 15, {
                'width': 170,
                'elementHandlers': specialElementHandlers
            });
            doc.save('عرض_مالي.pdf');
        }
    </script>
</body>
</html>
