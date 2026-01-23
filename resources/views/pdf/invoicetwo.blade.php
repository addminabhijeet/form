<!doctype html>
<html lang="null">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Laravel') }} - Invoice</title>
    <!-- Required Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body {
            background-color: #444;
            padding: 0 10px;
            margin: 0;
            min-width: fit-content;
        }
        .page-container {
            margin: 10px auto;
            width: fit-content;
        }
        .page {
            overflow: hidden;
            position: relative;
            background-color: white;
        }
        .annotations-container {
            position: absolute;
            pointer-events: none;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 3;
        }
        .annotations-container > div {
            position: absolute; pointer-events: auto; user-select: none; -webkit-user-select: none;
        }
        .annotations-container > div:hover {
            background-color: rgba(255, 255, 0, 0.25);
            cursor: pointer;
        }
    </style><style>
@media print {

    /* Define A4 page size */
    @page {
        size: A4;
        margin: 0;
    }

    body * {
        visibility: hidden; /* Hide everything by default */
    }

    .page-container,
    .page-container * {
        visibility: visible; /* Show only page-container and its children */
    }

    .page-container {
        position: absolute;
        left: 0;
        top: 0;

        /* A4 dimensions */
        width: 210mm;
        height: 297mm;

        margin: 0;
        padding: 0;
    }
}

</style>
    <style class="shared-css" type="text/css" >
.t {
	transform-origin: bottom left;
	z-index: 2;
	position: absolute;
	white-space: pre;
	overflow: visible;
	line-height: 1.5;
}
.text-container {
	white-space: pre;
}
@supports (-webkit-touch-callout: none) {
	.text-container {
		white-space: normal;
	}
}
</style>
<style id="fonts1" type="text/css" >

@font-face {
	font-family: Literata-Bold_j;
src: url(data:application/font-woff;charset=utf-8;base64,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) format("woff");
}

@font-face {
	font-family: Literata-Regular_p;
src: url(data:application/font-woff;charset=utf-8;base64,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) format("woff");
}

</style>
<style type="text/css" >

.s0{font-size:15px;font-family:Literata-Bold_j;color:#000;}
.s1{font-size:14px;font-family:Literata-Regular_p;color:#000;}
.s2{font-size:18px;font-family:Literata-Regular_p;color:#000;}
.s3{font-size:18px;font-family:Literata-Bold_j;color:#000;}
.s4{font-size:33px;font-family:Literata-Bold_j;color:#000;}
.s5{font-size:14px;font-family:Literata-Bold_j;color:#000;}
.s6{font-size:12px;font-family:Literata-Regular_p;color:#000;}
.s7{font-size:17px;font-family:Literata-Bold_j;color:#000;}
.s8{font-size:21px;font-family:Literata-Regular_p;color:#000;}
</style>
<script id="metadata" type="application/json">{"pagecount":1,"title":"","author":"","subject":"","keywords":"","creator":"","producer":"iLovePDF","creationdate":"","moddate":"D:20260122141229Z","trapped":"","fileName":"Sample Invoice-1.pdf","bounds":[[909,1286]],"bookmarks":[],"thumbnailType":"","pageType":"html","pageLabels":[]}</script>
<script id="annotations" type="application/json">{"pages":[{"page":1,"annotations":[{"type":"Link","bounds":[63,423,155,23],"objref":"65"},{"type":"Link","bounds":[218,423,5,23],"objref":"67"},{"type":"Link","bounds":[87,454,142,18],"objref":"69"},{"type":"Link","bounds":[87,478,28,19],"objref":"71"},{"type":"Link","bounds":[124,503,78,18],"objref":"73"},{"type":"Link","bounds":[57,1167,803,28],"objref":"75"},{"type":"Link","bounds":[289,1192,339,27],"objref":"77"}]}]}</script>

</head>
<body>
<button id="printBtn">Print PDF</button>

<div id="pagecontainer" class="page-container">

<section class="page" style="width: 909px; height: 1286px;" aria-label="Page 1">
<div id="pg1Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); user-select: none; -webkit-user-select: none;"></div>
<div id="pg1" style="user-select: none; -webkit-user-select: none;"><img id="pdf1" style="width:909px; height:1286px;" src="data:image/svg+xml,%3Csvg viewBox='0 0 909 1286' version='1.1' xmlns='http://www.w3.org/2000/svg'%3E%0A%3Cdefs%3E%0A%3CclipPath id='c0'%3E%3Cpath d='M-.2 1287.1V-.2H910.1V1287.1Z'/%3E%3C/clipPath%3E%0A%3CclipPath id='c1'%3E%3Cpath d='M37.7 592.4V371.7H872.1V592.4Z'/%3E%3C/clipPath%3E%0A%3CclipPath id='c2'%3E%3Cpath d='M36.3 593.3v-223H873.5v223Z'/%3E%3C/clipPath%3E%0A%3CclipPath id='c3'%3E%3Cpath d='M36.3 880.1V617H873.7V880.1Z'/%3E%3C/clipPath%3E%0A%3CclipPath id='c4'%3E%3Cpath d='M36.3 967.2V905.8H873.7v61.4Z'/%3E%3C/clipPath%3E%0A%3CclipPath id='c5'%3E%3Cpath d='M61.7 471.7V456.2H77.3v15.5Z'/%3E%3C/clipPath%3E%0A%3CclipPath id='c6'%3E%3Cpath d='M61.7 494.9V479.4H77.3v15.5Z'/%3E%3C/clipPath%3E%0A%3CclipPath id='c7'%3E%3Cpath d='M64.7 541.6V526h9.8v15.6Z'/%3E%3C/clipPath%3E%0A%3CclipPath id='c8'%3E%3Cpath d='M62.4 518.3V503.9H76.8v14.4Z'/%3E%3C/clipPath%3E%0A%3CclipPath id='c9'%3E%3Cpath d='M499.2 471.7V456.2h15.6v15.5Z'/%3E%3C/clipPath%3E%0A%3CclipPath id='ca'%3E%3Cpath d='M499.2 494.9V479.4h15.6v15.5Z'/%3E%3C/clipPath%3E%0A%3CclipPath id='cb'%3E%3Cpath d='M502.2 519V503.5H512V519Z'/%3E%3C/clipPath%3E%0A%3CclipPath id='cc'%3E%3Cpath d='M776.7 131.7V-.2H910.1V131.7Z'/%3E%3C/clipPath%3E%0A%3Cstyle%3E%0A.g0%7Bfill:%23FFF%3B%7D%0A.g1%7Bfill:none%3Bstroke:%23000%3Bstroke-width:4.585%3Bstroke-miterlimit:10%3B%7D%0A.g2%7Bfill:%23B4B4B4%3B%7D%0A.g3%7Bfill:%23000%3B%7D%0A.g4%7Bfill:none%3Bstroke:%23000%3Bstroke-width:2.266%3Bstroke-miterlimit:10%3B%7D%0A.g5%7Bfill:%23B3AAA5%3B%7D%0A.g6%7Bfill:%23000%3Bfill-opacity:0%3B%7D%0A%3C/style%3E%0A%3C/defs%3E%0A%3Cg clip-path='url(%23c0)'%3E%0A%3Cpath d='M0 0H909.8V1286.8H0V0Z' class='g0'/%3E%0A%3Cpath clip-path='url(%23c0)' d='M0 0H910.1V1287.3H0V0Z' class='g0'/%3E%0A%3Cimage clip-path='url(%23c1)' preserveAspectRatio='none' x='37' y='371' width='836' height='222' href='data:image/png%3Bbase64%2CiVBORw0KGgoAAAANSUhEUgAAA0QAAADeCAMAAAAq0jCkAAAAMFBMVEUAAACzs7OysrK2trawsLC1tbWzs7O2trazs7O0tLS3t7e0tLS0tLS0tLS1tbWzs7NQAvCQAAAAEHRSTlMAOVMqKlOy/4OC/7D/gf9UUU9Q4AAAAuVJREFUeNrt08sNwzAMREEptuVfHPffbW68qACCwLwSdjGtSZIkSZIkSZJUu/5RmZa4bd2skVWfEI1dZTritvOyRlZjQnR/VaYnbnt%2B1sjqhggiQQQRRBAJIogEEUQQQSSIIBJEgggiQQQRRBAJIogEkSCCSBBBZBSIBBFEgkgQQSSIIBJEgggiQSSIIBJEEAkiQQSRIBJEEAkiiASRIIJIEAkiiAQRRIJIEEEkiAQRRIIIIkEkiCASRIIIIkEEkSASRBAJIkEEkSCCSBAJIogEEUQQQSSIIBJEEEEEkSCCCCKIBBFEgggiiCASRBAJIkEEkSCCCCKIBBFEgkgQQSSIIBJEgggiQSSIIBJEEAkiQQSRIBJEEAkiiASRIIJIEAkiiAQRRIJIEEEkiAQRRIIIIkEkiCASRIIIIkEEkSASRBAJIkEEkSCCSBAJIogEEUQQQSSIIBJEEEEEkSCCCCKIBBFEgggiiCASRBAJIkEEkSCCCCKIBBFEgkgQQSSIIDIKRIIIIkEkiCASRBAJIkEEkSASRBAJIogEkSCCSBAJIogEEUSCSBBBJIgEEUSCCCJBJIggEkSCCCJBBJEgEkQQCSJBBJEggkgQCSKIBJEggkgQQSSIBBFEgggiiCASRBAJIogggkgQQQQRRIIIIkEEEUQQCSKIBJEggkgQQQQRRIIIIkEkiCASRBAJIkEEkSASRBAJIogEkSCCSBAJIogEEUSCSBBBJIgEEUSCCCJBJIggEkSCCCJBBJEgEkQQCSJBBJEggkgQCSKIBJEggkgQQSSIBBFEgggiiCASRBAJIogggkgQQQQRRIIIIkEEEUQQCSKIBJEggkgQQQQRRIIIIkEkiCASRBAZBSJBBJEgEkQQCSKIBJEggkgQCSKIBBFEgkgQQSSIBBFEgggiQSSIIBJEgggiQQSRIBJEEAkiQVQB0dhVpiNuOy9rZDUmRH1TmZa4bX2tkVVvkiRJkiRJkiTV7g9u2ZEdl46kTAAAAABJRU5ErkJggg=='/%3E%0A%3Cpath clip-path='url(%23c2)' d='M36.6 370.6V593.5H873.2V370.6H36.6' class='g1'/%3E%0A%3Cpath d='M37.5 618.1V694H383V618.1H37.5Zm344.9 0V694h166V618.1h-166Zm165.4 0V694H662.2V618.1H547.8Zm113.8 0V694H872.3V618.1H661.6Z' class='g2'/%3E%0A%3Cpath d='M37.8 619.5V876.6M382.7 619.5V876.6M548.1 619.5V876.6M661.9 619.5V876.6M872 619.5V876.6M36.6 618.4H873.2M36.6 693.7H873.2M36.6 877.7H873.2Z' class='g3'/%3E%0A%3Cpath clip-path='url(%23c3)' d='M37.8 619.5V876.6M382.7 619.5V876.6M548.1 619.5V876.6M661.9 619.5V876.6M872 619.5V876.6M36.6 618.4H873.2M36.6 693.7H873.2M36.6 877.7H873.2' class='g4'/%3E%0A%3Cpath d='M37.5 906.9v58.5H662.9V906.9H37.5Z' class='g0'/%3E%0A%3Cpath d='M37.8 908.3V964m624.8-55.7V964M872 908.3V964M36.6 907.2H873.2M36.6 965.1H873.2Z' class='g3'/%3E%0A%3Cpath clip-path='url(%23c4)' d='M37.8 908.3V964m624.8-55.7V964M872 908.3V964M36.6 907.2H873.2M36.6 965.1H873.2' class='g4'/%3E%0A%3Cpath clip-path='url(%23c5)' d='M77.4 464.2c0 .5 0 1-.1 1.5c-.1 .5-.3 1-.4 1.4c-.2 .5-.5 .9-.8 1.4c-.2 .4-.6 .8-.9 1.1c-.4 .4-.8 .7-1.2 1c-.4 .3-.8 .5-1.3 .7c-.5 .2-1 .3-1.4 .4c-.5 .1-1 .2-1.5 .2c-.6 0-1.1-.1-1.5-.2c-.5-.1-1-.2-1.5-.4c-.5-.2-.9-.4-1.3-.7c-.4-.3-.8-.6-1.2-1c-.3-.3-.7-.7-.9-1.1c-.3-.5-.6-.9-.7-1.4c-.2-.4-.4-.9-.5-1.4c-.1-.5-.1-1-.1-1.5c0-.5 0-1 .1-1.5c.1-.5 .3-1 .5-1.4c.1-.5 .4-1 .7-1.4c.2-.4 .6-.8 .9-1.1c.4-.4 .8-.7 1.2-1c.4-.3 .8-.5 1.3-.7c.5-.2 1-.3 1.5-.4c.4-.1 .9-.2 1.5-.2c.5 0 1 .1 1.5 .2c.4 .1 .9 .2 1.4 .4c.5 .2 .9 .4 1.3 .7c.4 .3 .8 .6 1.2 1c.3 .3 .7 .7 .9 1.1c.3 .4 .6 .9 .8 1.4c.1 .4 .3 .9 .4 1.4c.1 .5 .1 1 .1 1.5Z' class='g3'/%3E%0A%3Cpath d='M65.1 461.4c.1-.1 .2-.1 .3-.1H74c.2 0 .3 .1 .3 .1l-4.6 2.5l-4.6-2.5Zm9.4 5.4c0 0 0-.1 0-.2v-4.7l-2.8 1.5l2.8 3.4Zm-9.6-5v4.8c0 0 0 .1 0 .1l2.9-3.3l-2.9-1.6Zm6.3 1.8l-1.3 .8q-.1 0-.2 0c0 0-.1 0-.1 0l-1.4-.8l-2.9 3.5c0 0 .1 0 .1 0H74l-2.8-3.5Z' class='g2'/%3E%0A%3Cpath clip-path='url(%23c6)' d='M69.7 479.9c-.5 0-.9 0-1.4 .1c-.5 .1-.9 .2-1.4 .4c-.4 .2-.8 .4-1.2 .7c-.4 .3-.8 .6-1.1 .9c-.4 .3-.7 .7-.9 1.1c-.3 .4-.5 .8-.7 1.3c-.2 .4-.3 .9-.4 1.3c-.1 .5-.2 1-.2 1.5c0 .4 .1 .9 .2 1.4c.1 .5 .2 .9 .4 1.4c.2 .4 .4 .8 .7 1.2c.2 .4 .5 .8 .9 1.1c.3 .4 .7 .7 1.1 .9c.4 .3 .8 .5 1.2 .7c.5 .2 .9 .3 1.4 .4c.5 .1 .9 .1 1.4 .1c.5 0 1 0 1.4-.1c.5-.1 1-.2 1.4-.4c.4-.2 .9-.4 1.3-.7c.4-.2 .7-.5 1.1-.9c.3-.3 .6-.7 .9-1.1c.2-.4 .5-.8 .6-1.2c.2-.5 .4-.9 .5-1.4c.1-.5 .1-1 .1-1.4c0-.5 0-1-.1-1.5c-.1-.4-.3-.9-.5-1.3c-.1-.5-.4-.9-.6-1.3c-.3-.4-.6-.8-.9-1.1c-.4-.3-.7-.6-1.1-.9c-.4-.3-.9-.5-1.3-.7c-.4-.2-.9-.3-1.4-.4c-.4-.1-.9-.1-1.4-.1Zm4.5 10.7c-.2 .7-1.1 1.6-1.9 1.6c-1.2 0-2.9-1.2-4.4-3l-.6-.7c-1.5-1.9-2.3-3.7-2.1-4.9c.1-.8 1.4-1.5 2.1-1.5c.3 0 .4 .2 .5 .3c.4 .7 .9 1.9 .9 2.4c-.1 .3-.3 .4-.5 .5c-.2 .1-.4 .2-.4 .4c0 .1 .1 .4 1.1 1.7l.4 .5c1.1 1.2 1.4 1.3 1.4 1.4c.3 0 .4-.1 .6-.3c.1-.2 .3-.3 .5-.4h.1c.4 .1 1.6 .9 2.2 1.4c.1 .1 .3 .2 .1 .6Z' class='g3'/%3E%0A%3Cpath clip-path='url(%23c7)' d='M65 532.7c0 2.7 4.8 9 4.8 9c0 0 4.7-6.3 4.7-9c-.1-6.4-9.4-6.4-9.5 0Zm4.8 2.4c-1.5 0-2.6-1.1-2.6-2.6c0-1.4 1.1-2.6 2.6-2.6c1.4 0 2.6 1.2 2.6 2.6c0 1.5-1.2 2.6-2.6 2.6Z' class='g3'/%3E%0A%3Cpath d='M68.2 504.4c-.1 0-.1 .1-.2 .1q-.1 0-.2 0c-.1 0-.1 .1-.2 .1c-.1 0-.2 0-.3 .1c0 0-.1 0-.2 0c0 .1-.1 .1-.2 .1c0 .1-.1 .1-.2 .1c0 0-.1 .1-.2 .1q-.1 .1-.2 .1q-.1 .1-.2 .1q-.1 .1-.2 .2c0 0-.1 0-.2 .1c0 0-.1 .1-.2 .1c0 .1-.1 .1-.1 .2c-.1 0-.2 0-.2 .1c-.1 0-.1 .1-.2 .2c0 0-.1 .1-.1 .1c-.1 .1-.2 .1-.2 .2c-.1 0-.1 .1-.2 .1c0 .1-.1 .2-.1 .2q-.1 .1-.2 .2c0 .1-.1 .1-.1 .2c-.1 0-.1 .1-.1 .2c-.1 0-.1 .1-.2 .2c0 0-.1 .1-.1 .2c.8 .3 1.8 .5 2.9 .7c.3-1.8 .9-3.2 1.6-4Z' class='g3'/%3E%0A%3Cpath d='M72 508.5c-.4-2.6-1.4-4.2-2.4-4.2c-.9 0-1.9 1.6-2.3 4.2c1.5 .1 3.2 .1 4.7 0Z' class='g3'/%3E%0A%3Cpath clip-path='url(%23c8)' d='M66.4 511.2c0-.8 .1-1.5 .1-2.2c-1.1-.1-2.2-.4-3.1-.7c-.4 .9-.7 1.8-.7 2.9c0 1 .3 1.9 .7 2.8c.9-.3 2-.5 3.1-.7c0-.7-.1-1.4-.1-2.1Z' class='g3'/%3E%0A%3Cpath d='M75.6 507.7h-.1c0-.1 0-.2-.1-.2c0-.1-.1-.2-.1-.2c-.1-.1-.1-.2-.1-.2c-.1-.1-.1-.2-.2-.2c0-.1-.1-.1-.1-.2c-.1 0-.1-.1-.2-.2c0 0-.1-.1-.1-.1q-.1-.1-.2-.2c-.1 0-.1-.1-.2-.1c0-.1-.1-.2-.1-.2c-.1-.1-.2-.1-.2-.1q-.1-.1-.2-.2c-.1 0-.1-.1-.2-.1c0-.1-.1-.1-.2-.1c0-.1-.1-.1-.2-.2c0 0-.1 0-.2-.1c0 0-.1 0-.2-.1c0 0-.1-.1-.2-.1c0 0-.1 0-.2-.1c0 0-.1 0-.2-.1q-.1 0-.2 0c-.1-.1-.2-.1-.2-.1c-.1 0-.2-.1-.2-.1c-.1 0-.2 0-.3 0c0 0-.1-.1-.1-.1c.7 .8 1.2 2.2 1.5 4c1.1-.2 2.1-.4 3-.7Z' class='g3'/%3E%0A%3Cpath d='M67.2 509.1c-.1 .6-.2 1.3-.2 2.1c0 .7 .1 1.4 .2 2c1.5-.2 3.3-.2 4.9 0c.1-.6 .1-1.3 .1-2c0-.8 0-1.5-.1-2.1c-.8 .1-1.6 .1-2.5 .1c-.8 0-1.7 0-2.4-.1Z' class='g3'/%3E%0A%3Cpath d='M72.7 509c.1 .7 .1 1.4 .1 2.2c0 .7 0 1.4-.1 2.1c1.2 .2 2.3 .4 3.2 .7c.4-.9 .6-1.8 .6-2.8c0-1.1-.2-2-.6-2.9c-.9 .3-2 .6-3.2 .7Z' class='g3'/%3E%0A%3Cpath d='M71.1 517.9c0 0 .1 0 .1-.1c.1 0 .2 0 .3 0c0 0 .1-.1 .2-.1c0 0 .1 0 .2-.1q.1 0 .2 0q.1-.1 .2-.1c.1-.1 .2-.1 .2-.1c.1 0 .2-.1 .2-.1c.1 0 .2-.1 .2-.1c.1-.1 .2-.1 .2-.1c.1-.1 .2-.1 .2-.1c.1-.1 .2-.1 .2-.2c.1 0 .1-.1 .2-.1q.1-.1 .2-.2c0 0 .1 0 .1-.1c.1 0 .2-.1 .2-.1q.1-.1 .2-.2c0 0 .1-.1 .2-.2c0 0 .1-.1 .1-.1q.1-.1 .2-.2c0-.1 .1-.1 .1-.2c.1 0 .1-.1 .2-.2c0 0 0-.1 .1-.1c0-.1 .1-.2 .1-.3c.1 0 .1-.1 .1-.1c0-.1 .1-.1 .1-.1c-.9-.3-1.9-.5-3-.7c-.3 1.8-.8 3.2-1.5 4Z' class='g3'/%3E%0A%3Cpath d='M63.7 514.6v.1c0 0 .1 .1 .1 .1c.1 .1 .1 .2 .2 .3c0 0 0 .1 .1 .1c0 .1 .1 .2 .1 .2q.1 .1 .2 .2c0 .1 .1 .1 .1 .2c.1 0 .1 .1 .2 .1c0 .1 .1 .2 .2 .2c0 .1 .1 .1 .1 .2c.1 0 .1 .1 .2 .1q.1 .1 .2 .1c0 .1 .1 .1 .2 .2c0 0 .1 .1 .1 .1c.1 .1 .2 .1 .2 .2c.1 0 .2 0 .2 .1c.1 0 .2 0 .2 .1c.1 0 .1 .1 .2 .1c.1 0 .2 .1 .2 .1c.1 0 .2 0 .2 .1c.1 0 .2 0 .3 .1c0 0 .1 0 .1 0c.1 .1 .2 .1 .3 .1c.1 0 .1 .1 .2 .1c.1 0 .2 0 .2 0q.1 .1 .2 .1c-.7-.8-1.3-2.2-1.6-4c-1.1 .2-2.1 .4-2.9 .7Z' class='g3'/%3E%0A%3Cpath clip-path='url(%23c8)' d='M67.3 513.8c.4 2.6 1.4 4.2 2.3 4.2c1 0 2-1.6 2.4-4.2c-1.5-.1-3.2-.1-4.7 0Z' class='g3'/%3E%0A%3Cpath clip-path='url(%23c9)' d='M514.9 464.2c0 .5 0 1-.1 1.5c-.1 .5-.3 1-.5 1.4c-.2 .5-.4 .9-.7 1.4c-.3 .4-.6 .8-.9 1.1c-.4 .4-.8 .7-1.2 1c-.4 .3-.9 .5-1.3 .7c-.5 .2-1 .3-1.5 .4c-.5 .1-1 .2-1.5 .2c-.5 0-1-.1-1.5-.2c-.5-.1-.9-.2-1.4-.4c-.5-.2-.9-.4-1.3-.7c-.5-.3-.8-.6-1.2-1c-.4-.3-.7-.7-1-1.1c-.2-.5-.5-.9-.7-1.4c-.2-.4-.3-.9-.4-1.4c-.1-.5-.2-1-.2-1.5c0-.5 .1-1 .2-1.5c.1-.5 .2-1 .4-1.4c.2-.5 .5-1 .7-1.4c.3-.4 .6-.8 1-1.1c.4-.4 .7-.7 1.2-1c.4-.3 .8-.5 1.3-.7c.5-.2 .9-.3 1.4-.4c.5-.1 1-.2 1.5-.2c.5 0 1 .1 1.5 .2c.5 .1 1 .2 1.5 .4c.4 .2 .9 .4 1.3 .7c.4 .3 .8 .6 1.2 1c.3 .3 .6 .7 .9 1.1c.3 .4 .5 .9 .7 1.4c.2 .4 .4 .9 .5 1.4c.1 .5 .1 1 .1 1.5Z' class='g3'/%3E%0A%3Cpath d='M502.6 461.4c.1-.1 .2-.1 .3-.1h8.6c.1 0 .2 .1 .3 .1l-4.6 2.5l-4.6-2.5Zm9.4 5.4c0 0 0-.1 0-.2v-4.7l-2.8 1.5l2.8 3.4Zm-9.6-5v4.8c0 0 0 .1 0 .1l2.9-3.3l-2.9-1.6Zm6.3 1.8l-1.4 .8h-.1c0 0-.1 0-.1 0l-1.4-.8l-2.9 3.5c0 0 .1 0 .1 0h8.6l-2.8-3.5Z' class='g5'/%3E%0A%3Cpath clip-path='url(%23ca)' d='M507.2 479.9c-.5 0-1 0-1.4 .1c-.5 .1-1 .2-1.4 .4c-.4 .2-.9 .4-1.3 .7c-.4 .3-.7 .6-1.1 .9c-.3 .3-.6 .7-.9 1.1c-.2 .4-.5 .8-.6 1.3c-.2 .4-.4 .9-.5 1.3c0 .5-.1 1-.1 1.5c0 .4 .1 .9 .1 1.4c.1 .5 .3 .9 .5 1.4c.1 .4 .4 .8 .6 1.2c.3 .4 .6 .8 .9 1.1c.4 .4 .7 .7 1.1 .9c.4 .3 .9 .5 1.3 .7c.4 .2 .9 .3 1.4 .4c.4 .1 .9 .1 1.4 .1c.5 0 .9 0 1.4-.1c.5-.1 .9-.2 1.4-.4c.4-.2 .8-.4 1.2-.7c.4-.2 .8-.5 1.1-.9c.4-.3 .7-.7 1-1.1c.2-.4 .4-.8 .6-1.2c.2-.5 .3-.9 .4-1.4c.1-.5 .2-1 .2-1.4c0-.5-.1-1-.2-1.5c-.1-.4-.2-.9-.4-1.3c-.2-.5-.4-.9-.6-1.3c-.3-.4-.6-.8-1-1.1c-.3-.3-.7-.6-1.1-.9c-.4-.3-.8-.5-1.2-.7c-.5-.2-.9-.3-1.4-.4c-.5-.1-.9-.1-1.4-.1Zm4.5 10.7c-.2 .7-1.1 1.6-1.9 1.6c-1.2 0-2.9-1.2-4.4-3l-.6-.7c-1.5-1.9-2.3-3.7-2.1-4.9c.1-.8 1.3-1.5 2-1.5c.4 0 .5 .2 .6 .3c.4 .7 .9 1.9 .9 2.4h-.1c0 .3-.2 .4-.4 .5c-.2 .1-.4 .2-.4 .4c0 .1 .1 .4 1.1 1.7l.4 .5c1 1.2 1.3 1.3 1.4 1.4c.3 0 .4-.1 .6-.3c.1-.2 .2-.3 .5-.4h.1c.4 .1 1.5 .9 2.2 1.4c.1 .1 .2 .2 .1 .6Z' class='g3'/%3E%0A%3Cpath clip-path='url(%23cb)' d='M502.5 510.2c0 2.7 4.7 8.9 4.7 8.9c0 0 4.8-6.2 4.8-8.9c-.1-6.4-9.4-6.4-9.5 0Zm4.7 2.4c-1.4 0-2.6-1.2-2.6-2.6c0-1.5 1.2-2.6 2.6-2.6c1.5 0 2.6 1.1 2.6 2.6c0 1.4-1.1 2.6-2.6 2.6Z' class='g3'/%3E%0A%3Cimage clip-path='url(%23cc)' preserveAspectRatio='none' x='777' y='-1' width='133' height='132' href='data:image/png%3Bbase64%2CiVBORw0KGgoAAAANSUhEUgAAAIUAAACECAMAAACJWBirAAADAFBMVEUAAAAKCQdmXVhAAEAODQqXjogLCwuAgIBHPjliWVRPRkEKCQmdlI%2BAv796cWxVVQAQDgwLCggAgID///8KCgr//wB9c26vp6FMRUH/AP9kXFaJfnpZUUslJRkSFA8VFxOKgXwJBgSpnppuXlgaGhhAQABlXFdKQTwYGRYuIyBLQj1/d3G/tbGck45tZF8AAGocHBb/gICYj4oZGRYAGhp4b2pJbW0YFxEA/wAVFRFAQECmnZdbUU2tpJ%2B8tK8qKysVFBEXHSCGfXiAAABTPT1tamoOEA6FfHekm5ZFPDeLdHQVGyEWFhRjWlVfWFGZmZkdHR1GPTgcHCIiIhxQRkESEhBDLykSEhAUFA8ZGRRhXFEWERG7sq2KgHyJgHsXGBUQDg2qVVVwZ2HKtLQdHRGfgIAWGBIPDwoVFRIZGhgPEQ2MhH8aGhmwp6QPEA0aHBkZGhceFx64sKwQDwy2rahpYFy7sq2VjIgUEg6AQEBIPTxSSUSmnpkREQ4yNTUYGRYSDhZ2bWcSCRIODAsYGRYcHRkZGhgkJSR2dk8WEg6PhoFAVUBlXFcODgyuqqkUFA9ZLS0aGhgSEBCYjosTEhANDQ0UEg4aGhloX1ozAABmZmYRDgywp6JZTFRlXFYOEQ1LSzwVExBORUFSSUSelZBlXFgcHBzDurWKgXw8LShyaGOMg34SCw1RSEIREQ4cFhFPRkEdERNvZmCqoZyxqKMKCQh3cWsZGRkeHh4QDQqAXFyupaEPDgx1dWoUEhBQR0I8NTBzamUiGRkSExAMCwkREQ5SR0IXFRIeGBiwpqIVFQAPEQublIqJgHslHRsVEg4LEA40NB4LDQgNDQoiICBPRkESFBBVS0aJgXoWFhcfIBq9tLBfVlESFA9ERCQdGhWupJ%2BKgXxZWS0REQ4SEhIVExAWGBQbHBkREQ4SEhBVVVVyaWR6amkTExwVFhVRSEO/trEcHBi5sKtVTEdQRkEREA0ZGRYuFxcNCQdjWloNDQpmXFgRDxFUSj%2BtnpseFxb4VMIfAAABAHRSTlMAcGoEz2gVA8TJcCnNBMgD668CAUQBadQxASoq/Rb8N9SHKRLqBIz8%2By63h9y//wIoAv1RCv4H1QGpBUym/XANbCZKAgwMiv39SgwX/f5GBTCWJS390hHt9zMWVEiu/rRtA7IFFQn%2BMvusyGhGmv3KjSIxuP3U/lHrBBb8kfQHryOWHVLQO88KBjj/DPyPCtsFdlkycCzPF/0FBTCyDrFxDJL4q/r8Df/8KGv/RP/NOsoXTf7%2BjystRG4HTc8X6U4X/jSpk2snSyr4DLcSk0FrOAyhdw%2BN9dAWJi%2BN/qoITHqJBYwV1/5ptIsEyRUbEMb7UMv%2B0U5mC3QTq6wrFBYqT7c2wgAACjBJREFUeNrt2w9UE/cdAPCbUyjoLFZXmUD8l2lkljIYYyKKMgXWBmcZCkghbzYLSUADxD9MC2o1Ulk0EtEwtX0kEET549S0TGWhFJC6WotGUmidfzo4EWvEoXSKdfv9LpfkknB2L96RvLd83%2BO9u/wO7sP397vfn7sLgrjDHe5whzvc4dRYeckVFEfnuILi%2BH9cQXHxY1dQXN/lCorQ466gGKxxBUXrdldQ6OSuoIj5gSsoWlxCMYGgeCt34cLcC9blM08eOzaFdoVYZ97c89Xc/v65a2YQi0/eaWoKu3MswvETFJq3FPWGSpKDhDFmxMIj/f0bNJq3/2ApnbK%2BCSjCwk46auAObinOwbYMgyJmuKhYMtxRnsLbps3cuUc2rFunWRC3z1zaN0saFAQZ2xxESAQsFsoJRpCfSgRMFGyzmMUK%2B8MKGpQ/xzff7N%2BwYZ3mVFzFGIsiMl5aCh13VjliqCyGZ2ahgkrky2wRkwV3WCyBPaOsQbnXlIv%2BDTATcRUbzaV7I/2vxscDx%2Br3HGoSoSLsvExYDYqsbCbcQVkCu9YRNbrZNM3J/RfMRFzFOD9z6a%2B9B/yhQz3L0RrZgf3/xcZzSURYavBdQgTImr81bZ89fx4gKjIJxZsCa6HDy%2BFrtdhYCfh/v3wnhtppWyddo3vN%2BfGc/0rcuHGZr1lNxd54oPoi0tfh6/Q%2BphCZTnsfqxTWLZujPvHp9bDsXdi3r9PT%2BoCITb6%2BZY73FlnYaUXB%2BO7rW4y5KbQ%2BKkUmNCk89%2BzZ89u3PG3/zkHPpWfO9Dmq%2BKtRkWDav8WyUuFRo60ba2qdX8FYmGvleO/kNhjHZlKkkGD7zHrroyYl9Ww19RdHsJj75q8Io8g22HOGxY6nSpGwA0uGTQ%2B6XS87ZKUAI0mupWc9hiHCwsZ/Q5Gie1iFPF/7xKIA4wjoP9c8NVXKwdVhuOJvtCpa8/MsuYAGMJScn3wDH1ffHT9CiuT8DMI4AgzrNBrNgslvY9OMM6ubRkRxmpE81aQwGxaAjnzjsmV/RPpWNxlHdpoVOj5DYlbAcf0UMJwCijGR6qFlq1YHGRl0KxiMNtMwAsf1ovSQOGCIq8iMLFWr318fFAQH9iaaFTF8RqpJATORHhub/sq4CjCmRUql6tLS0iDMQa9i4m4ew7QgOQsQcUAR%2B/KFMeNCMiPBiB4PIZBxh1ZFQQub0WpSgAYRVw0VCNK58cPyq1ctjju09lrfXmYzSvDt%2BQBRUZ2eDhUgGsv9r0LIVTDpK6VXceUxURFHVCCbgMPff2DAH8y11i%2BlU6HoZTBMk/D5YJ4VUmRRIMi1xi8GNm8eAAnxekSnolvM45kV4MoIqSYqEM/AgdrNA6BmvFbRqfAQ8nhKfHtjRUiIjaIvcHOtceJJs4KNzjMpQuwUXwcm1sJk%2BHt9R6dibJ21oshOARgDAwP3qFLcHU6xv4eH9uLb0SFFRbBxEhR7A2cnAsdmxxUSkYwZzhSYFR4cuB9ukwuZtaI6PdZGAQKk494mBxWViq6uLgXXsubH9ruuWOdCi6JifGYVXVSNIWItC/SIN2ZjjESHFf9TZOSjqBCf5kdXgQCIn50hLIpegArV7BdoVbQDRR1%2Bhm%2BisfjwDPGAj36Dhe9eOhU1DBRdEeXsu1pta1G0J8fZCjlQyH7nEgqJ0%2B8/g3ahdYrilwV3t%2B7fv7%2B9fdKkGiUfRZm6mknt7Rljn2y9eyXqR1Sd5SevRkQVFlYaDIZuD25ATkJ9/a6poanykpKYmJiWlsuPJ3wqFB5YIdNqtUl8NgqCrU/K08pkPQeE4k8fP77cotwdo9Mtksvbjk%2BtX7x48ese3d0Gwy8qCwsL%2Bl763rP/8Oici9dT5Z%2BX6GI%2BaLkMziYWhsu0eXl5Pj75%2BfnJyXw%2BGwbPHKg5AAZ%2BYC7j8/nJyfn5ST4%2BSQDbUycWQ13LB9P/fbpVnpr18ZyPnqloS5UvOq3TTQeMx73CA%2BEymdbHxycvSa8HCD50oMSzWwXmgj%2BAymcl6/X6JMDAklQnngAUAKHDFBefobDMkPoiCrAa4XI9uNyA4PqUrOupn7eW6G6DKmlu7hWDGqlbIZMlJbONNcLWM7WynhV1QqG4t7l5tzLmdklJq1x%2BvS2lPoGr8IA18vfKysKCF/t%2BTFWzmVjGvXQiZ%2ByTQ%2B27Hj5UgmskSdf2sD3j0JOtOScCuNf2OOF6WcRg87RO7y%2B2u0SvBRQ8WYKzFQ%2BBosfpT5dr1vLQhgBnK9qhYqWzFRl6MOO7Rt/f9%2Bsc5sNHtvf2nyRZZr80hGdamp/dh1Nm2a70sDk4famInlY9LdM6HatGeZVKR9nkYgWK/oM2xMGb1WBxkRZtSfZLU4bUanXp0EybFSLPvELE4lIUCNMOmBsYnkfROa0aRtVNvz5TZayXqmFYt4x3hNaKYM6OnTuz8ZXTcs4OkSj7eZrFzaoqgKiqmnbTD6uM9fHx0CAdemR754CtJOynwMWtCO9A4FMEVPBcyfisqtqYDtA8RnmpjYmQvm9zkXSLeex/2io4BAXruRRIZ5qRkV5188bLamNI7Z7CKXp55jtKdCggowprGmfP3xhlVNg/Cqzstdxdo0VhrJSqz2Yc1kyeMUsqta8OEFHNfHuFiEoF8hpooml%2BNzQazRo/wBga5t5UlJK9tpXWXCDI0sw0v9zJQHH%2B7KOhWcM9pJ6oZDPkNCuQ33ciM4wKZNW7wx5xm814SLcCxNM1QHH4KVmxjm1%2BMkFTuzDG2cOHD98gLT3NYxwfgVx8T5xmMKaOhOKcd0eH7zMW7fqMkVA0qhJV3uQKvflJJq3twru2traD/DaK5amuJRcKyhV9HUBRTnrrdrteRlQEwxvEO7ooV5QFAsWDc6QLkrye/cTRjQPfWJFQrjj3AChUpM2zRlv3DnE/G76ykk25wleVWJuY2EhWnKIVexD3s%2BC7TcxQqhWN8NlCbQfZyyT1MmtFoQC%2BRcS8b6BW4Q0RieUFJMVdDb0nrD/AXrFibhnMUlCoKMcU95aQFAfI5tm8qVPPgdlAWYIc6hR/ugcV5BdJ4eh5tm%2BSKQYF4cydg1wKawS7RMBFQtY8N41W2t/uNwRLgiltF40qFawR0j68oEFJukylTuENEeBSJevDPRtuI7QrIjpqjVFeRnKEcAQUS8pxxV/ImucEHekvZz332gyP7x7gCtI%2B/BkK7H3EbAoUsP82Ngyyi%2BQy6ZvYy7EHsNepmeLgyfAmuRSmExcCCV8SOlEOHNhEXKr6b2PzJHmZWkf8noBh8FYw1pW%2ByjW%2BLMukIhVIuQpHJN4juYfWOsmqv7olyi4OHRzMxgwoc5CasUyFM1QdJLmw/eaGRMCELyujcIDnhFIz6fzaG%2B87O8j6i1S779LUF8/jhDNFnOz73VStN/ZiDHLEsN/oqeQG53QpqFz2wGyQVgfsmlJGYvGFRDQ%2B6FhCXpzyyYgokDLfZ73CMcclvvV2dKUrKP7ch7jDHe5whzv%2BX%2BO/ogZxOuzNMxQAAAAASUVORK5CYII='/%3E%0A%3C/g%3E%0A%3Cpath d='M62 472.3H77.4V456.9H62v15.4Z' class='g6'/%3E%0A%3Cpath d='M62 494.8H76.7V480.1H62v14.7Z' class='g6'/%3E%0A%3Cpath d='M62.7 518.6h14v-14h-14v14Z' class='g6'/%3E%0A%3Cpath d='M65 542.1h9.5V526.7H65v15.4Z' class='g6'/%3E%0A%3Cpath d='M499.4 472.3h15.3V456.9H499.4v15.4Z' class='g6'/%3E%0A%3Cpath d='M499.4 494.8H514V480.1H499.4v14.7Z' class='g6'/%3E%0A%3Cpath d='M502.3 519.5h9.5V504.1h-9.5v15.4Z' class='g6'/%3E%0A%3C/svg%3E"/></div>
<div class="text-container"><span class="t s0" style="left:53px;bottom:572px;letter-spacing:0.05px;font-size:12px;"><strong>
{{
    $invoice->package === 'career_starter' ? 'Career Starter' :
    ($invoice->package === 'growth_package' ? 'Growth Package' :
    ($invoice->package === 'career_acceleration' ? 'Career Acceleration' : ''))
}}
</span></strong>
<span class="t s1" style="left:76px;bottom:558px;letter-spacing:-0.18px;font-size:10px;">
{{
    $invoice->package === 'career_starter' ? 'High-impact, ATS-optimized resume, Expert-crafted,' :
    ($invoice->package === 'growth_package' ? 'High-impact, ATS-optimized resume, Expert-crafted,' :
    ($invoice->package === 'career_acceleration' ? 'High-impact, ATS-optimized resume, Expert-crafted,' : ''))
}}
</span>
<span class="t s1" style="left:76px;bottom:540px;letter-spacing:-0.18px;font-size:10px;">
{{
    $invoice->package === 'career_starter' ? 'Personalized cover letter, Delivery in Word & PDF formats,' :
    ($invoice->package === 'growth_package' ? 'Personalized cover letter, Delivery in Word & PDF formats,' :
    ($invoice->package === 'career_acceleration' ? 'Personalized cover letter, Delivery in Word & PDF formats,' : ''))
}}
</span>
<span class="t s1" style="left:76px;bottom:522px;letter-spacing:-0.18px;font-size:10px;">
{{
    $invoice->package === 'career_starter' ? 'Priority delivery: 1–2 working days' :
    ($invoice->package === 'growth_package' ? 'Priority delivery: 1–2 working days, Advanced mock interview' :
    ($invoice->package === 'career_acceleration' ? 'Priority delivery: 1–2 working days, Advanced mock interview' : ''))
}}
</span>
<span class="t s1" style="left:76px;bottom:504px;letter-spacing:-0.18px;font-size:10px;">
{{
    $invoice->package === 'career_starter' ? '' :
    ($invoice->package === 'growth_package' ? 'preparation, Complete LinkedIn profile optimization,' :
    ($invoice->package === 'career_acceleration' ? 'preparation, Complete LinkedIn profile optimization,' : ''))
}}
</span>
<span class="t s1" style="left:76px;bottom:486px;letter-spacing:-0.18px;font-size:10px;">
{{
    $invoice->package === 'career_starter' ? '' :
    ($invoice->package === 'growth_package' ? 'One-on-one career guidance by a senior expert,' :
    ($invoice->package === 'career_acceleration' ? 'One-on-one career guidance by a senior expert,' : ''))
}}
</span>
<span class="t s1" style="left:76px;bottom:468px;letter-spacing:-0.18px;font-size:10px;">
{{
    $invoice->package === 'career_starter' ? '' :
    ($invoice->package === 'growth_package' ? '' :
    ($invoice->package === 'career_acceleration' ? 'Personalized digital card for professional branding,' : ''))
}}
</span>
<span class="t s1" style="left:76px;bottom:450px;letter-spacing:-0.18px;font-size:10px;">
{{
    $invoice->package === 'career_starter' ? '' :
    ($invoice->package === 'growth_package' ? '' :
    ($invoice->package === 'career_acceleration' ? 'Real-world case study demonstrating expertise,' : ''))
}}
</span>
<span class="t s1" style="left:76px;bottom:432px;letter-spacing:-0.18px;font-size:10px;">
{{
    $invoice->package === 'career_starter' ? '' :
    ($invoice->package === 'growth_package' ? '' :
    ($invoice->package === 'career_acceleration' ? 'Exclusive webinars with experienced HR managers,' : ''))
}}
</span>
<span class="t s1" style="left:76px;bottom:414px;letter-spacing:-0.18px;font-size:10px;">
{{
    $invoice->package === 'career_starter' ? '' :
    ($invoice->package === 'growth_package' ? '' :
    ($invoice->package === 'career_acceleration' ? 'Ongoing interview updates with top-tier companies' : ''))
}}
</span>
<span class="t s2" style="left:37px;bottom:1112px;letter-spacing:0.18px;">Invoice</span>
<span class="t s3" style="left:103px;bottom:1112px;"># </span>
<span class="t s3" style="left:178px;bottom:1112px;letter-spacing:0.21px;font-weight:700;">{{ $invoice->invoice_number }}</span>
<span class="t s2" style="left:178px;bottom:1081px;letter-spacing:0.15px;">{{ $invoice->invoice_date }}</span>
<span class="t s4" style="left:37px;bottom:1147px;letter-spacing:1.1px;">INVOICE </span>
<span class="t s5" style="left:414px;bottom:25px;letter-spacing:0.03px;">THANK YOU </span>
<span class="t s0" style="left:62px;bottom:838px;letter-spacing:0.18px;">NORYAAN SYSTEMS </span>
<span class="t s0" style="left:225px;bottom:838px;letter-spacing:0.15px;">LLP. </span>
<span class="t s6" style="left:88px;bottom:812px;letter-spacing:0.13px;">accounts@noryaan.com </span>
<span class="t s6" style="left:88px;bottom:742px;letter-spacing:0.12px;">Mani Casadona, Action Area 1, New Town </span>
<span class="t s6" style="left:88px;bottom:726px;letter-spacing:0.11px;">West, Kolkata - 700156, West Bengal </span>
<span class="t s6" style="left:88px;bottom:788px;letter-spacing:0.11px;">+91 9147704137 </span>
<span class="t s6" style="left:88px;bottom:764px;letter-spacing:0.14px;">www.noryaan.com </span>
<span class="t s6" style="left:345px;bottom:711px;letter-spacing:0.13px;">PAN : AAYFN8278K </span>
<span class="t s7" style="left:62px;bottom:867px;letter-spacing:-0.19px;">Bill From: </span>
<span class="t s7" style="left:500px;bottom:867px;letter-spacing:-0.17px;">Bill To: </span>
<span class="t s8" style="left:123px;bottom:617px;letter-spacing:0.07px;">Item Description </span>
<span class="t s8" style="left:411px;bottom:617px;letter-spacing:0.05px;">Rate (INR) </span>
<span class="t s8" style="left:589px;bottom:619px;letter-spacing:0.05px;">Qty. </span>
<span class="t s8" style="left:695px;bottom:619px;letter-spacing:0.06px;">Amount (INR) </span>
<span class="t s8" style="left:450px;bottom:504px;letter-spacing:0.06px;">
₹{{
    $invoice->package === 'career_starter' ? 2999 :
    ($invoice->package === 'growth_package' ? 3999 :
    ($invoice->package === 'career_acceleration' ? 4999 : 0))
}}</span></span>
<span class="t s8" style="left:599px;bottom:503px;">1</span>
<span class="t s8" style="left:752px;bottom:504px;letter-spacing:0.06px;">
₹{{
    $invoice->package === 'career_starter' ? 2999 :
    ($invoice->package === 'growth_package' ? 3999 :
    ($invoice->package === 'career_acceleration' ? 4999 : 0))
}}</span></span>
<span class="t s8" style="left:283px;bottom:331px;letter-spacing:0.07px;">Sub Total </span>
<span class="t s8" style="left:752px;bottom:334px;letter-spacing:0.06px;">
₹{{
    $invoice->package === 'career_starter' ? 2999 :
    ($invoice->package === 'growth_package' ? 3999 :
    ($invoice->package === 'career_acceleration' ? 4999 : 0))
}}</span></span>
<span class="t s2" style="left:56px;bottom:88px;letter-spacing:0.17px;">Payment has been successfully received. Your onboarding process is now active, and further </span>
<span class="t s2" style="left:288px;bottom:64px;letter-spacing:0.16px;">details will be shared with you shortly. </span>
<span class="t s2" style="left:37px;bottom:1081px;letter-spacing:0.16px;">Invoice Date </span>
<span class="t s2" style="left:156px;bottom:1112px;">: </span>
<span class="t s2" style="left:156px;bottom:1081px;">: </span>
<span class="t s0" style="left:500px;bottom:838px;letter-spacing:0.17px;"><strong style="text-transform: uppercase;">{{ $invoice->candidate_name }}</strong></span>
<span class="t s6" style="left:525px;bottom:812px;letter-spacing:0.1px;">{{ $invoice->candidate_email }}</span>
<span class="t s6" style="left:525px;bottom:764px;letter-spacing:0.1px;"></span>
<span class="t s6" style="left:525px;bottom:730px;letter-spacing:0.1px;">{{ $invoice->candidate_address }}</span>
<span class="t s6" style="left:525px;bottom:788px;letter-spacing:0.1px;">+91{{ $invoice->candidate_mobile }}</span></div>

</div>

</div>

<script>
    const metadata = JSON.parse(document.getElementById("metadata").text);
    document.title = metadata.title || metadata.fileName;

    const annotations = JSON.parse(document.getElementById("annotations").text);
    const pages = document.querySelectorAll(".page");

    const createAnnotation = function(container, data, pageNo) {
        if (data.type !== "Link" && data.type !== "TextLink") {
            return;
        }
        if (!data.action) {
            return;
        }

        const annotation = document.createElement("div");
        annotation.setAttribute("style", "");
        annotation.style.left = data.bounds[0] + "px";
        annotation.style.top = data.bounds[1] + "px";
        annotation.style.width = data.bounds[2] + "px";
        annotation.style.height = data.bounds[3] + "px";
        annotation.dataset.type = data.type;
        if (data.objref) {
            annotation.dataset.objref = data.objref;
        }

        if (data.appearance) {
            annotation.style.backgroundImage = "url('" + data.appearance + "')";
            annotation.style.backgroundSize = "100% 100%";
        }

        if (data.action.type === "URI") {
            const element = document.createElement("a");
            element.href = data.action.uri;
            element.title = data.action.uri;
            element.target = "_blank";
            element.style.position = "absolute";
            element.style.width = "100%";
            element.style.height = "100%";
            annotation.appendChild(element);
        } else {
            annotation.addEventListener("click", () => {
                switch (data.action.type) {
                    case "GoTo":
                        pages[data.action.page - 1].scrollIntoView();
                        break;

                    case "Named":
                        switch (data.action.name) {
                            case "NextPage":
                                pages[pageNo - 2].scrollIntoView();
                                break;
                            case "PrevPage":
                                pages[pageNo].scrollIntoView();
                                break;
                            case "FirstPage":
                                pages[0].scrollIntoView();
                                break;
                            case "LastPage":
                                pages[metadata.pagecount - 1].scrollIntoView();
                                break;
                        }
                        break;
                }
            });
        }
        container.append(annotation);
    };

    annotations.pages.forEach(pageAnnotations => {
        const pageNo = pageAnnotations.page;
        const annotationsContainer = document.createElement("div");
        annotationsContainer.className = "annotations-container";
        annotationsContainer.style.width = metadata.bounds[pageNo - 1][0];
        annotationsContainer.style.height = metadata.bounds[pageNo - 1][1];
        pageAnnotations.annotations.forEach(annotation => createAnnotation(annotationsContainer, annotation, pageNo));
        pages[pageNo - 1].appendChild(annotationsContainer);
    });
</script>


<script>
document.getElementById("printBtn").addEventListener("click", function() {
    const pageContainer = document.querySelector(".page-container");

    // Hide all other elements on the page (including the print button)
    const bodyChildren = Array.from(document.body.children);
    bodyChildren.forEach(el => {
        if (el !== pageContainer) {
            el.style.display = 'none';
        }
    });

    // Print only the page-container
    window.print();

    // Restore original display
    bodyChildren.forEach(el => {
        if (el !== pageContainer) {
            el.style.display = '';
        }
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dom-to-image-more@2.9.0/dist/dom-to-image-more.min.js"></script>
<script>
document.getElementById("downloadBtn").addEventListener("click", async function () {
    const element = document.getElementById("pagecontainer");

    // ✅ Clone element for isolated rendering
    const clonedElement = element.cloneNode(true);

    // ✅ Minimal print isolation (NOT style matching)
    const printStyle = document.createElement("style");
    printStyle.textContent = `
        @page {
            size: A4 portrait;
            margin: 0;
        }

        body {
            margin: 0 !important;
            background: #fff !important;
        }

        .page-container {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
        }
    `;
    clonedElement.prepend(printStyle);

    // ✅ Allow layout & assets to settle
    await new Promise(resolve => setTimeout(resolve, 5));

    // ✅ A4 pixel dimensions
    const a4WidthPx = 1175;
    const a4HeightPx = Math.round(a4WidthPx * 1.4142);

    // ✅ html2pdf options (logic-aligned)
    const opt = {
        margin: [0, 0, 0, 0],
        filename: 'page.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: {
            scale: 3,
            useCORS: true,
            scrollY: 0,
            backgroundColor: "#ffffff",
            logging: false,
            letterRendering: true
        },
        jsPDF: {
            unit: 'px',
            format: [a4WidthPx, a4HeightPx],
            orientation: 'portrait'
        },
        pagebreak: {
            mode: ['avoid-all', 'css', 'legacy']
        }
    };

    // ✅ Convert Iconify icons → images (FIXED)
    clonedElement.querySelectorAll("iconify-icon").forEach(icon => {
        const img = document.createElement("img");
        const iconName = icon.getAttribute("icon");

        img.src = `https://api.iconify.design/${iconName}.svg?color=%23000`;
        img.width = 34;
        img.height = 34;
        img.style.filter = "contrast(250%) brightness(0%)";

        icon.replaceWith(img);
    });

    // ✅ Generate PDF
    await html2pdf().set(opt).from(clonedElement).save();
});
</script>



</body>
</html>
