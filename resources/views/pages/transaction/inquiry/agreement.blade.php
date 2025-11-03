@extends('layouts.print')
@section('content')
<br><br>
<div class="container" style="font-family: Calibri;font-size:18px;">
    <div class="row">
        <div class="col-md-6 text-left">
            <img class="img-fluid" src="{{ asset('LOGO RAKITEK.png') }}" width="150">
        </div>
        <div class="col-md-6 text-right">
            <br><br>
            <p style="font-size:18px;">{{ date("j F Y", strtotime($inquiry->created_at)) }}</p>
        </div>

        <div class="col-md-12">
            <br>
            <table class="table table-borderless" width="100%">
                <tbody>
                    <tr>
                        <td width="10%">No. Ref</td>
                        <td width="5%">:</td>
                        <td >{{$inquiry->code}}</td>
                    </tr>
                    <tr>
                        <td width="10%">Perihal</td>
                        <td width="5%">:</td>
                        <td >Penawaran Sewa Virtual Office</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-12">
            <br>
            <table class="table table-borderless" width="100%">
                <tbody>
                    <tr>
                        <td width="10%">Kepada YTH</td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td width="10%"><b>{{$inquiry->contact->name}}</b></td>
                    </tr>
                    <tr>
                        <td width="10%"><b>{{$inquiry->customer->name}}</b></td>
                    </tr>
                    <tr>
                        <td width="10%">Di Tempat</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-12">
            <p>Dengan Hormat,</p><br>
            <p>Sebelumnya kami mengucapkan terima kasih atas keinginan Bapak untuk menjadi salah satu tenant
            di Menara 165.</p><br>
            <p>Menindak lanjuti permintaan sebelumnya, bersama ini kami sampaikan Surat Penawaran sewa 165<br>
            Suite Virtual Office di Menara 165 dengan fasilitas dan ketentuan sebagai berikut :</p>
            <br>
            <p><b>Harga &amp; Ketentuan</b></p>
            <table width="100%" class="table table-bordered">
                <thead>
                    <tr style="background-color: #4169E1 !important;color: #FFF;">
                        <th class="text-center">
                            Fasilitas
                        </th>
                        <th class="text-center">Virtual Office
                        Ekonomis
                        </th>
                        <th class="text-center">Virtual Office
                        Business</th>
                        <th class="text-center">Virtual Office
                        Premium</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">
                            Alamat Kantor di Menara 165
                        </td>
                        <td class="text-center">&#10004</td>
                        <td class="text-center">&#10004</td>
                        <td class="text-center">&#10004</td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Surat Masuk
                        </td>
                        <td class="text-center">-</td>
                        <td class="text-center">&#10004</td>
                        <td class="text-center">&#10004</td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Line Telephone
                        </td>
                        <td class="text-center">-</td>
                        <td class="text-center">&#10004</td>
                        <td class="text-center">&#10004</td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            No Fax
                        </td>
                        <td class="text-center">-</td>
                        <td class="text-center">&#10004</td>
                        <td class="text-center">&#10004</td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Resepsionis
                        </td>
                        <td class="text-center">-</td>
                        <td class="text-center">&#10004</td>
                        <td class="text-center">&#10004</td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Area Lounge
                        </td>
                        <td class="text-center">-</td>
                        <td class="text-center">&#10004</td>
                        <td class="text-center">&#10004</td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Domisili Kelurahan
                        </td>
                        <td class="text-center">-</td>
                        <td class="text-center">&#10004</td>
                        <td class="text-center">&#10004</td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Domisili Gedung
                        </td>
                        <td class="text-center">&#10004</td>
                        <td class="text-center">&#10004</td>
                        <td class="text-center">&#10004</td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Meeting Room**)
                        </td>
                        <td class="text-center">-</td>
                        <td class="text-center">2 jam / bulan</td>
                        <td class="text-center">8 jam/bulan</td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Meja Kerja / Workstations
                        </td>
                        <td class="text-center">-</td>
                        <td class="text-center">5 hari / bulan</td>
                        <td class="text-center">10 hari/bulan</td>
                    </tr>
                    <tr>
                        <td class="text-center">

                        </td>
                        <td class="text-center"><b>Rp 200.000,-/bulan</b></td>
                        <td class="text-center"><b>Rp 500.000,-/bulan</b></td>
                        <td class="text-center"><b>Rp 1.000.000,-/bulan</b></td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            &nbsp;
                        </td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                    </tr>
                    <tr style="background-color: #4169E1 !important;color: #FFF;">
                        <td class="text-center">
                            Ilustrasi Pembayaran (12 bulan)
                        </td>
                        <td class="text-center"><b>Rp 2,400,000,-</b></td>
                        <td class="text-center"><b>Rp 6,000,000,-</b></td>
                        <td class="text-center"><b>Rp 12,000,000,-</b></td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Diskon 20 %
                        </td>
                        <td class="text-center"><b>Rp        ,-/bulan</b></td>
                        <td class="text-center"><b>Rp 4,800,000,-</b></td>
                        <td class="text-center"><b>Rp 9,600,000,-</b></td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Pajak (VAT/PPN)
                        </td>
                        <td class="text-center"><b>Rp 240,000,-</b></td>
                        <td class="text-center"><b>Rp 480,000,-</b></td>
                        <td class="text-center"><b>Rp 960,000,-</b></td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Total Pembayaran Termasuk Pajak
                        </td>
                        <td class="text-center"><b>Rp 2,640,000,-</b></td>
                        <td class="text-center"><b>Rp 5,280,000,-</b></td>
                        <td class="text-center"><b>Rp 10,560,000,-</b></td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Biaya Materai (2 Buah)
                        </td>
                        <td class="text-center"><b>Rp 12,000,-</b></td>
                        <td class="text-center"><b>Rp 12,000,-</b></td>
                        <td class="text-center"><b>Rp 12,000,-</b></td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <b>Total Pembayaran Keseluruhan</b>
                        </td>
                        <td class="text-center"><b>Rp 2,652,000,-</b></td>
                        <td class="text-center"><b>Rp 5,292,000,-</b></td>
                        <td class="text-center"><b>Rp 10,572,000,-</b></td>
                    </tr>
                </tbody>
            </table>
            <p style="margin-left:25px">
                <ul>
                    <li>Berlaku untuk periode kontrak 12 bulan*)</li>
                    <li>Pengurusan legalitas hanya untuk SKDP Gedung dan Kelurahan*)</li>
                    <li>Kapasitas 4 dan atau kapasitas 8 **)</li>
                </ul>
            </p>
        </div>
    </div>
</div>
<div style="page-break-after: always">

</div>
<br><br>
<div  class="container" style="font-family: Calibri;font-size:18px;">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-borderless" width="100%">
                <tbody>
                    <tr>
                        <td width="20%">Additional Charge</td>
                        <td >:</td>
                    </tr>


                </tbody>
            </table>
            <table class="table table-borderless" >
                <tbody>
                    <tr>
                        <td >1.</td>
                        <td >Aplikasi Call Forwarding</td>
                        <td >Rp 250.000,-/ Aplikasi</td>
                    </tr>
                    <tr>
                        <td >2.</td>
                        <td >Telephone Activation (Dedicated)</td>
                        <td >Rp 450,000,-</td>
                    </tr>
                    <tr>
                        <td >3.</td>
                        <td >Ruangan PKP</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered" width="100%">
                <thead>
                    <tr style="background-color: #4169E1 !important;color: #FFF;">
                        <td>Room</td>
                        <td>Person Capacity</td>
                        <td>Price</td>
                        <td>Quaterly
                            Payment
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Diamond D
                            (8 hari kerja/Bulan)
                        </td>
                        <td>1 Person
                        </td>
                        <td>1,000,000,-/month
                        </td>
                        <td>3,000,000,-
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ruang Meeting &amp; Mini Auditorium</p>
            <table class="table table-bordered" width="100%">
                <thead>
                    <tr style="background-color: #4169E1 !important;color: #FFF;">
                        <td class="text-center">Room</td>
                        <td class="text-center">Person Capacity</td>
                        <td class="text-center">Price/ Hour</td>
                        <td class="text-center">
                            Full Day Meeting (8 Hours)
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td >Meeting Room 1
                        </td>
                        <td>4
                        </td>
                        <td>110K
                        </td>
                        <td>700K
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td>Meeting Room 2
                        </td>
                        <td>8
                        </td>
                        <td>200K
                        </td>
                        <td>1,200K
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td>Meeting Room 3
                        </td>
                        <td>10
                        </td>
                        <td>250K
                        </td>
                        <td>1,500K
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td>Meeting Room 4
                        </td>
                        <td>14
                        </td>
                        <td>350K
                        </td>
                        <td>2,100K
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td>Meeting Room 5
                        </td>
                        <td>22
                        </td>
                        <td>550K
                        </td>
                        <td>4,000K
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td>Mini Auditorium
                        </td>
                        <td>25
                        </td>
                        <td>675K
                        </td>
                        <td>4,050K
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>Cara Pembayaran :</p>
            <table class="table table-borderless" >
                <tbody>
                    <tr>
                        <td >1.</td>
                        <td >Pembayaran 12 Bulan dimuka.</td>
                    </tr>
                    <tr>
                        <td >2.</td>
                        <td >Transfer ke rekening<br>
                        <ul>
                                <li>BCA 5995055519 PT. Griya Bangun Persada</li>
                                <li>Bank Syariah Mandiri 7000539117 PT. Griya Bangun Persada</li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>Demikian kami sampaikan, apabila terdapat pertanyaan atau informasi yang lain, mohon
                menghubungi kami di nomor (021) 5081-2002 atau (021) 5081-2003.
                Atas perhatian dan kerjasama yang baik, kami ucapkan terima kasih .
            </p><br><br>
            <p>Hormat kami,<br>
            <b>{{$company_name}}</b>
            <br>
            <br>
            <br>
            <br>
            <br>
            <u>
            <u><b>Test</b></u><br>
            Marketing Manager
            </p>
        </div>
    </div>
</div>
@endsection
