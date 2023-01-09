<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            font-size: 15px;
        }

        .clr {
            clear: both
        }

        .orange-bg {
            background: #f36f21;
            color: #fff;
            font-size: 16px;
        }

        .white-bg {
            background: #fff;
        }

        .container {
            width: 800px;
            margin: auto
        }

        .orange-hdr {
            font-size: 20px;
            padding: 10px;
        }

        .p10 {
            padding: 10px;
        }

        table {
            margin: 0;
            padding: 0;
            border-spacing: 0
        }

        table table tr td {
            border-right: 2px solid #fff;
        }

        .frc-box-info-table .border-orange {
            border-bottom: 3px solid #DB5F14;
        }

        .frc-box-info-table .pb-7 {
            padding-bottom: 7px;
        }

        .frc-box-info-table .pt-7 {
            padding-top: 7px;
        }

        .frc-box-info-table {
            color: #5E5B59;
            width: 725px;
        }

    </style>
</head>
<body>
<div class="container white-bg">
    <div class="orange-bg orange-hdr p10" style="font-weight: bold;width: 710px">Check {{ $file->name }} {{ date("d.n.Y H:i") }}</div>
    <div class="clr" style="margin-bottom: 5px;"></div>

        <table class="frc-box-info-table" style="padding-top: 20px;">
            <tr>
                <td class="border-orange pb-7" style="width: 380px; font-size: 17px"><b>Message</b></td>
                <td class="border-orange pb-7" style="width: 120px; font-size: 17px">Row</td>
            </tr>
            @foreach($validationStructureErrors as $error)
            <tr>
                <td class="pt-7"><b>{{ $error->message }}</b></td>
                <td class="pt-7">{{ $error->line }} </td>
            </tr>
            @endforeach
        </table>
        <br>
        <br>
</div>
</body>
</html>
