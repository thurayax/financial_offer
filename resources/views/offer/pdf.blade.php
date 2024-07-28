<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>عرض مالي - {{ $client->name }}</title>
    <style>
        @font-face {
            src: url('{{ storage_path('fonts/Amiri-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: 'Amiri', serif;
            direction: rtl;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>عرض مالي</h1>
        <p>السادة: {{ $client->name }}</p>
        <p>عنوان العميل: {{ $client->address }}</p>
        <p>التاريخ: {{ now()->format('Y-m-d') }}</p>
        <p>اسم المسؤول: {{ $client->manager_name }}</p>

        <table>
            <thead>
                <tr>
                    <th>السعر الإجمالي</th>
                    <th>الكمية</th>
                    <th>سعر الخدمة</th>
                    <th>الخدمة</th>
                    <th>رقم البند</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offers as $offer)
                <tr>
                    <td>{{ $offer->total_price }}</td>
                    <td>{{ $offer->quantity }}</td>
                    <td>{{ $offer->service_price }}</td>
                    <td>{{ $offer->service }}</td>
                    <td>{{ $loop->iteration }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h2>بيان</h2>
        <div>{{ $client->statement }}</div>

        <h2>سياسة الدفع</h2>
        <p>1. يتم تحويل المبلغ على حسب الدفعات التالية:</p>
        <p>50% كدفعة أولى</p>
        <p>30% كدفعة ثانية</p>
        <p>20% كدفعة ثالثة</p>
        <p>2. يتم تحويل المبلغ إلى حساب جمعية عون التقنية - البنك الأهلي - IBAN SA3310000000200000444207</p>
    </div>
</body>
</html>
