<?php
// Ultra-simple approach - just show simple page if no database
if (!getenv('DATABASE_URL')) {
    include("simple.php");
    exit;
}

// Try to start session and load database
try {
    session_start();
    include("db_connection.php");
    
    // Test database connection
    if (defined('DB_TYPE') && DB_TYPE === 'postgresql') {
        $test_query = $conn->query("SELECT 1");
        if (!$test_query) {
            throw new Exception("Database test failed");
        }
    } else {
        if (!$conn || mysqli_connect_error()) {
            throw new Exception("MySQL connection failed");
        }
    }
    
    // If we get here, database is working, try to load main page
    include("header.php");
    include("nav.php");
    
} catch (Exception $e) {
    // Any error, show simple page
    include("simple.php");
    exit;
}
?>

<div class="slider slider-4">
    <div id="rev_slider_7_1_wrapper" class="rev_slider_wrapper fullscreen-container" data-alias="hebes-home-4" data-source="gallery" style="background:transparent;padding:0px;">
        <!-- START REVOLUTION SLIDER 5.4.7 fullscreen mode -->
        <div id="rev_slider_7_1" class="rev_slider fullscreenbanner" style="display:none;" data-version="5.4.7">
            <ul> <!-- SLIDE  -->
                <li data-index="rs-21" data-transition="parallaxtotop" data-easein="Power4.easeInOut" data-easeout="Power4.easeInOut" data-masterspeed="2000" data-thumb="assets/images/slider/thumb/8.png" data-title="<p>NEXT UP</p><h6>Chair blue Decor</h6>">
                    <!-- MAIN IMAGE -->
                    <img src="assets/images/slider/bg/1.jpg" alt="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="off" class="rev-slidebg" data-no-retina>
                    <!-- LAYERS -->

                    <!-- LAYER NR. 1 -->
                    <div class="tp-caption   tp-resizeme" id="slide-21-layer-2" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['179','179','179','121']" data-fontsize="['14','14','14','13']" data-lineheight="['26','26','26','18']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":1500,"speed":1500,"frame":"0","from":"y:-50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 5; white-space: nowrap; font-size: 14px; line-height: 26px; font-weight: 700; color: #000000; letter-spacing: 3px;font-family:Montserrat;">
                        TRENDING SOFA <br>
                        COLLECTION <br>
                        2019 </div>

                    <!-- LAYER NR. 2 -->
                    <div class="tp-caption tp-shape tp-shapewrapper  tp-resizeme" id="slide-21-layer-3" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['167','167','167','104']" data-width="30" data-height="1" data-whitespace="nowrap" data-type="shape" data-responsive_offset="on" data-frames='[{"delay":1700,"speed":1500,"frame":"0","from":"y:-50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 6;background-color:rgb(0,0,0);"> </div>

                    <!-- LAYER NR. 3 -->
                    <div class="tp-caption   tp-resizeme" id="slide-21-layer-5" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['302','302','343','247']" data-fontsize="['120','120','100','60']" data-lineheight="['120','120','100','60']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":1600,"speed":2000,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 7; white-space: nowrap; font-size: 120px; line-height: 120px; font-weight: 700; color: #000000; letter-spacing: 0px;font-family:Montserrat;">
                        <span style="color:#606da6">X </span>Chair
                    </div>

                    <!-- LAYER NR. 4 -->
                    <div class="tp-caption rev-btn" id="slide-21-layer-6" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['580','580','730','571']" data-color="['rgb(96,109,166)','rgb(96,109,166)','rgb(0,0,0)','rgb(0,0,0)']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="button" data-responsive_offset="on" data-responsive="off" data-frames='[{"delay":3200,"speed":1500,"frame":"0","from":"x:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(0,0,0,1);bg:rgba(255,255,255,0);bs:solid;bw:0 0 0 0;"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 8; white-space: nowrap; font-size: 13px; line-height: 14px; font-weight: 700; color: #606da6; letter-spacing: 1px;font-family:Montserrat;background-color:rgba(0,0,0,0);border-color:rgba(0,0,0,1);outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">
                        <a href="#" class="btn btn--underlined">SHOP NOW</a>
                    </div>

                    <!-- LAYER NR. 5 -->
                    <div class="tp-caption   tp-resizeme" id="slide-21-layer-8" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['504','515','605','445']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2200,"speed":1500,"frame":"0","from":"x:200px;skX:-85px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 9; white-space: nowrap; font-size: 16px; line-height: 22px; font-weight: 400; color: #a1a1a1; letter-spacing: 0px;font-family:Montserrat;">
                        unique design <br>
                        Chair trending </div>

                    <!-- LAYER NR. 6 -->
                    <div class="tp-caption   tp-resizeme" id="slide-21-layer-9" data-x="['left','left','left','left']" data-hoffset="['289','285','303','232']" data-y="['top','top','top','top']" data-voffset="['503','516','601','443']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2300,"speed":1500,"frame":"0","from":"x:200px;skX:-85px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 10; white-space: nowrap; font-size: 16px; line-height: 22px; font-weight: 400; color: #a1a1a1; letter-spacing: 0px;font-family:Montserrat;">
                        the best material <br>
                        for the product </div>

                    <!-- LAYER NR. 7 -->
                    <div class="tp-caption   tp-resizeme" id="slide-21-layer-13" data-x="['left','left','left','left']" data-hoffset="['291','288','304','226']" data-y="['top','top','top','top']" data-voffset="['477','477','568','409']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2100,"speed":1000,"frame":"0","from":"x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 11; white-space: nowrap; font-size: 18px; line-height: 22px; font-weight: 700; color: #000000; letter-spacing: 0px;font-family:Montserrat;">
                        .02 </div>

                    <!-- LAYER NR. 8 -->
                    <div class="tp-caption   tp-resizeme" id="slide-21-layer-14" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['482','482','574','413']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2200,"speed":1000,"frame":"0","from":"x:-50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 12; white-space: nowrap; font-size: 18px; line-height: 22px; font-weight: 700; color: #000000; letter-spacing: 0px;font-family:Montserrat;">
                        .01 </div>
                </li>
                <!-- SLIDE  -->
                <li data-index="rs-24" data-transition="cube" data-easein="Power4.easeInOut" data-easeout="Power4.easeInOut" data-masterspeed="2000" data-thumb="assets/images/slider/thumb/8.png" data-title="<p>NEXT UP</p><h6>Chair blue Decor</h6>">
                    <!-- MAIN IMAGE -->
                    <img src="assets/images/slider/bg/2.jpg" alt="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="off" class="rev-slidebg" data-no-retina>
                    <!-- LAYERS -->

                    <!-- LAYER NR. 9 -->
                    <div class="tp-caption   tp-resizeme" id="slide-24-layer-2" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['180','180','180','121']" data-fontsize="['14','14','14','13']" data-lineheight="['26','26','26','18']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":1500,"speed":1500,"frame":"0","from":"x:-50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 5; white-space: nowrap; font-size: 14px; line-height: 26px; font-weight: 700; color: #000000; letter-spacing: 3px;font-family:Montserrat;">
                        TRENDING SOFA <br>
                        COLLECTION <br>
                        2019 </div>

                    <!-- LAYER NR. 10 -->
                    <div class="tp-caption tp-shape tp-shapewrapper  tp-resizeme" id="slide-24-layer-3" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['167','167','167','104']" data-width="30" data-height="1" data-whitespace="nowrap" data-type="shape" data-responsive_offset="on" data-frames='[{"delay":1600,"speed":1500,"frame":"0","from":"x:-50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 6;background-color:rgb(0,0,0);"> </div>

                    <!-- LAYER NR. 11 -->
                    <div class="tp-caption   tp-resizeme" id="slide-24-layer-5" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['302','302','344','247']" data-fontsize="['120','120','100','60']" data-lineheight="['120','120','100','60']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":1800,"split":"chars","splitdelay":0.05,"speed":2000,"split_direction":"forward","frame":"0","from":"y:[-100%];z:0;rZ:35deg;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 7; white-space: nowrap; font-size: 120px; line-height: 120px; font-weight: 700; color: #000000; letter-spacing: 0px;font-family:Montserrat;">
                        <span style="color:#606da6">Chair </span> Blue Decor
                    </div>

                    <!-- LAYER NR. 12 -->
                    <div class="tp-caption rev-btn" id="slide-24-layer-6" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['580','580','730','571']" data-color="['rgb(96,109,166)','rgb(96,109,166)','rgb(0,0,0)','rgb(0,0,0)']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="button" data-responsive_offset="on" data-responsive="off" data-frames='[{"delay":3000,"speed":1500,"frame":"0","from":"z:0;rX:0deg;rY:0;rZ:0;sX:2;sY:2;skX:0;skY:0;opacity:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power2.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(0,0,0,1);bg:rgba(255,255,255,0);bs:solid;bw:0 0 0 0;"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 8; white-space: nowrap; font-size: 13px; line-height: 14px; font-weight: 700; color: #606da6; letter-spacing: 1px;font-family:Montserrat;background-color:rgba(0,0,0,0);border-color:rgba(0,0,0,1);outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">
                        <a href="#" class="btn btn--underlined">SHOP NOW</a>
                    </div>

                    <!-- LAYER NR. 13 -->
                    <div class="tp-caption   tp-resizeme" id="slide-24-layer-8" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['504','515','605','445']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2000,"speed":1500,"frame":"0","from":"x:200px;skX:-85px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 9; white-space: nowrap; font-size: 16px; line-height: 22px; font-weight: 400; color: #a1a1a1; letter-spacing: 0px;font-family:Montserrat;">
                        unique design <br>
                        Chair trending </div>

                    <!-- LAYER NR. 14 -->
                    <div class="tp-caption   tp-resizeme" id="slide-24-layer-9" data-x="['left','left','left','left']" data-hoffset="['289','285','303','232']" data-y="['top','top','top','top']" data-voffset="['503','516','601','443']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2200,"speed":1500,"frame":"0","from":"x:200px;skX:-85px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 10; white-space: nowrap; font-size: 16px; line-height: 22px; font-weight: 400; color: #a1a1a1; letter-spacing: 0px;font-family:Montserrat;">
                        the best material <br>
                        for the product </div>

                    <!-- LAYER NR. 15 -->
                    <div class="tp-caption   tp-resizeme" id="slide-24-layer-13" data-x="['left','left','left','left']" data-hoffset="['291','288','304','226']" data-y="['top','top','top','top']" data-voffset="['477','477','568','409']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2200,"speed":1000,"frame":"0","from":"x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 11; white-space: nowrap; font-size: 18px; line-height: 22px; font-weight: 700; color: #000000; letter-spacing: 0px;font-family:Montserrat;">
                        .02 </div>

                    <!-- LAYER NR. 16 -->
                    <div class="tp-caption   tp-resizeme" id="slide-24-layer-14" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['482','482','574','413']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2100,"speed":1000,"frame":"0","from":"x:-50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 12; white-space: nowrap; font-size: 18px; line-height: 22px; font-weight: 700; color: #000000; letter-spacing: 0px;font-family:Montserrat;">
                        .01 </div>
                </li>
                <!-- SLIDE  -->
                <li data-index="rs-25" data-transition="incube-horizontal" data-easein="Power4.easeInOut" data-easeout="Power4.easeInOut" data-masterspeed="2000" data-thumb="assets/images/slider/thumb/8.png" data-title="<p>NEXT UP</p><h6>Chair blue Decor</h6>">
                    <!-- MAIN IMAGE -->
                    <img src="assets/images/slider/bg/3.jpg" alt="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="off" class="rev-slidebg" data-no-retina>
                    <!-- LAYERS -->

                    <!-- LAYER NR. 17 -->
                    <div class="tp-caption tp-resizeme" id="slide-25-layer-2" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['180','180','180','121']" data-fontsize="['14','14','14','13']" data-lineheight="['26','26','26','18']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":1500,"speed":1500,"frame":"0","from":"x:-50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 5; white-space: nowrap; font-size: 14px; line-height: 26px; font-weight: 700; color: #000000; letter-spacing: 3px;font-family:Montserrat;">
                        TRENDING SOFA <br>
                        COLLECTION <br>
                        2019 </div>

                    <!-- LAYER NR. 18 -->
                    <div class="tp-caption tp-shape tp-shapewrapper  tp-resizeme" id="slide-25-layer-3" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['167','167','167','104']" data-width="30" data-height="1" data-whitespace="nowrap" data-type="shape" data-responsive_offset="on" data-frames='[{"delay":1700,"speed":1500,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 6;background-color:rgb(0,0,0);"> </div>

                    <!-- LAYER NR. 19 -->
                    <div class="tp-caption tp-resizeme" id="slide-25-layer-5" data-x="['left','left','left','left']" data-hoffset="['88','88','80','25']" data-y="['top','top','top','top']" data-voffset="['302','302','344','247']" data-fontsize="['120','120','100','60']" data-lineheight="['120','120','100','60']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2200,"split":"chars","splitdelay":0.05,"speed":2000,"split_direction":"forward","frame":"0","from":"y:[100%];z:0;rZ:-35deg;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 7; white-space: nowrap; font-size: 120px; line-height: 120px; font-weight: 700; color: #000000; letter-spacing: 0px;font-family:Montserrat;">
                        Lamp <span style="color:#606da6">Wood</span> </div>

                    <!-- LAYER NR. 20 -->
                    <div class="tp-caption rev-btn" id="slide-25-layer-6" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['580','580','730','571']" data-color="['rgb(96,109,166)','rgb(96,109,166)','rgb(0,0,0)','rgb(0,0,0)']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="button" data-responsive_offset="on" data-responsive="off" data-frames='[{"delay":2800,"speed":1500,"frame":"0","from":"x:200px;skX:-85px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(0,0,0,1);bg:rgba(255,255,255,0);bs:solid;bw:0 0 0 0;"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 8; white-space: nowrap; font-size: 13px; line-height: 14px; font-weight: 700; color: #606da6; letter-spacing: 1px;font-family:Montserrat;background-color:rgba(0,0,0,0);border-color:rgba(0,0,0,1);outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">
                        <a href="#" class="btn btn--underlined">SHOP NOW</a>
                    </div>

                    <!-- LAYER NR. 21 -->
                    <div class="tp-caption   tp-resizeme" id="slide-25-layer-8" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['504','515','605','445']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2100,"speed":1500,"frame":"0","from":"x:200px;skX:-85px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 9; white-space: nowrap; font-size: 16px; line-height: 22px; font-weight: 400; color: #a1a1a1; letter-spacing: 0px;font-family:Montserrat;">
                        unique design <br>
                        Chair trending </div>

                    <!-- LAYER NR. 22 -->
                    <div class="tp-caption   tp-resizeme" id="slide-25-layer-9" data-x="['left','left','left','left']" data-hoffset="['289','285','303','232']" data-y="['top','top','top','top']" data-voffset="['503','516','601','443']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2200,"speed":1500,"frame":"0","from":"x:200px;skX:-85px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 10; white-space: nowrap; font-size: 16px; line-height: 22px; font-weight: 400; color: #a1a1a1; letter-spacing: 0px;font-family:Montserrat;">
                        the best material <br>
                        for the product </div>

                    <!-- LAYER NR. 23 -->
                    <div class="tp-caption   tp-resizeme" id="slide-25-layer-13" data-x="['left','left','left','left']" data-hoffset="['291','288','304','226']" data-y="['top','top','top','top']" data-voffset="['477','477','568','409']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2200,"speed":1000,"frame":"0","from":"x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 11; white-space: nowrap; font-size: 18px; line-height: 22px; font-weight: 700; color: #000000; letter-spacing: 0px;font-family:Montserrat;">
                        .02 </div>

                    <!-- LAYER NR. 24 -->
                    <div class="tp-caption   tp-resizeme" id="slide-25-layer-14" data-x="['left','left','left','left']" data-hoffset="['90','90','60','30']" data-y="['top','top','top','top']" data-voffset="['482','482','574','413']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":2100,"speed":1000,"frame":"0","from":"x:-50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 12; white-space: nowrap; font-size: 18px; line-height: 22px; font-weight: 700; color: #000000; letter-spacing: 0px;font-family:Montserrat;">
                        .01 </div>
                </li>
            </ul>
            <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
        </div>

    </div><!-- END REVOLUTION SLIDER -->
</div>
<script>
    var revapi7,
        tpj;
    (function() {
        if (!/loaded|interactive|complete/.test(document.readyState)) document.addEventListener("DOMContentLoaded", onLoad)
        else
            onLoad();

        function onLoad() {
            if (tpj === undefined) {
                tpj = jQuery;

                if ("off" == "on") tpj.noConflict();
            }
            if (tpj("#rev_slider_7_1").revolution == undefined) {
                revslider_showDoubleJqueryError("#rev_slider_7_1");
            } else {
                revapi7 = tpj("#rev_slider_7_1").show().revolution({
                    sliderType: "standard",
                    jsFileLocation: "assets/revolution/js/",
                    sliderLayout: "fullscreen",
                    dottedOverlay: "none",
                    delay: 9000,
                    navigation: {
                        keyboardNavigation: "off",
                        keyboard_direction: "horizontal",
                        mouseScrollNavigation: "off",
                        mouseScrollReverse: "default",
                        onHoverStop: "off",
                        arrows: {
                            style: "hermes",
                            enable: true,
                            hide_onmobile: false,
                            hide_onleave: false,
                            tmp: '<div class="tp-arr-allwrapper">	<div class="tp-arr-imgholder"></div>	<div class="tp-arr-titleholder">{{title}}</div>	</div>',
                            left: {
                                h_align: "left",
                                v_align: "center",
                                h_offset: 50,
                                v_offset: 0
                            },
                            right: {
                                h_align: "right",
                                v_align: "center",
                                h_offset: 50,
                                v_offset: 0
                            }
                        }
                        //							,
                        //							tabs: {
                        //								style:"metis",
                        //								enable:true,
                        //								width:100,
                        //								height:50,
                        //								min_width:0,
                        //								wrapper_padding:0,
                        //								wrapper_color:"transparent",
                        //								tmp:'<div class="tp-tab-wrapper"><div class="tp-tab-number">{{param1}}</div><div class="tp-tab-divider"></div><div class="tp-tab-title-mask"><div class="tp-tab-title">{{title}}</div></div></div>',
                        //								visibleAmount: 5,
                        //								hide_onmobile: false,
                        //								hide_onleave:false,
                        //								hide_delay:200,
                        //								direction:"vertical",
                        //								span:false,
                        //								position:"inner",
                        //								space:5,
                        //								h_align:"left",
                        //								v_align:"center",
                        //								h_offset:0,
                        //                                v_offset:20
                        //							}
                    },
                    responsiveLevels: [1240, 1024, 778, 480],
                    visibilityLevels: [1240, 1024, 778, 480],
                    gridwidth: [1240, 1024, 778, 480],
                    gridheight: [700, 768, 960, 720],
                    lazyType: "none",
                    parallax: {
                        type: "mouse",
                        origo: "enterpoint",
                        speed: 400,
                        speedbg: 0,
                        speedls: 0,
                        levels: [5, 10, 15, 20, 25, 30, 35, 40, 45, 46, 47, 48, 49, 3, 2, 55],
                    },
                    shadow: 0,
                    spinner: "spinner0",
                    stopLoop: "off",
                    stopAfterLoops: -1,
                    stopAtSlide: -1,
                    shuffle: "off",
                    autoHeight: "off",
                    fullScreenAutoWidth: "off",
                    fullScreenAlignForce: "off",
                    fullScreenOffsetContainer: "",
                    fullScreenOffset: "",
                    disableProgressBar: "on",
                    hideThumbsOnMobile: "off",
                    hideSliderAtLimit: 0,
                    hideCaptionAtLimit: 0,
                    hideAllCaptionAtLilmit: 0,
                    debugMode: false,
                    fallbacks: {
                        simplifyAll: "off",
                        nextSlideOnWindowFocus: "off",
                        disableFocusListener: false,
                    }
                });
                var api = revapi7;

                /* no need to edit below */
                var divider = ' / ',
                    totalSlides,
                    numberText;

                api.one('revolution.slide.onloaded', function() {

                    totalSlides = api.revmaxslide();
                    numberText = api.find('.slide-status-numbers').text('1' + divider + totalSlides);

                    api.on('revolution.slide.onbeforeswap', function(e, data) {

                        numberText.text((data.nextslide.index() + 1) + divider + totalSlides);

                    });

                });
            }; /* END OF revapi call */
        }; /* END OF ON LOAD FUNCTION */
    }()); /* END OF WRAPPING FUNCTION */
</script>
<!-- about #1
============================================= -->
<section id="about1" class="about about-1 pt-140 pt-60-xs pb-120 pb-60-xs">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6">
                <div class="heading heading-2">
                    <p class="heading--subtitle">HISTORY SINCE 1998</p>
                    <h2 class="heading--title">Welcome to Hebes Store - Amazing furniture</h2>
                </div>
            </div>
            <!-- .col-lg-6 end -->
            <div class="col-sm-12 col-md-12 col-lg-6">
                <div class="about--text">
                    <p>The third Monday of January is supposed to be the most depressing day of the year.
                        Whether you <span> believe that </span>or not, the long nights, cold weather and trying
                        to keep it</p>
                    <p>Actually, Woodstock was not the first outdoor festival to feature multiple bands over
                        several days performing on a stage set up out in the middle of a farmer’s field</p>
                </div>
                <div class="about--signature">JONT HENRRY<span>CEO</span></div>
            </div>
            <!-- .col-lg-6 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #about1 end -->

<!-- feature #1
============================================= -->
<section id="feature1" class="feature feature-1 pt-0 pb-0">
    <div class="container">
        <div class="row">
            <!--  feature panel #1 -->
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <i class="icon-bolt"></i>
                    </div>
                    <!-- .feature-icon end -->
                    <div class="feature--content">
                        <h3>Creative &amp; Unique</h3>
                        <p>GREAT FROM HEBES</p>
                    </div>
                    <!-- .feature-content end -->
                </div>
            </div>
            <!-- .feature-panel end -->
            <!--  feature panel #2 -->
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <i class="icon-location"></i>
                    </div>
                    <!-- .feature-icon end -->
                    <div class="feature--content">
                        <h3>Free Shipping</h3>
                        <p>ALL ORDER OVER $30</p>
                    </div>
                    <!-- .feature-content end -->
                </div>
            </div>
            <!-- .feature-panel end -->
            <!--  feature panel #3 -->
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <i class="icon-phone"></i>
                    </div>
                    <!-- .feature-icon end -->
                    <div class="feature--content">
                        <h3>Support Customer</h3>
                        <p>SUPPORT 24/7</p>
                    </div>
                    <!-- .feature-content end -->
                </div>
            </div>
            <!-- .feature-panel end -->
            <!--  feature panel #4 -->
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <i class="icon-link icon-md"></i>
                    </div>
                    <!-- .feature-icon end -->
                    <div class="feature--content">
                        <h3>Secure Payment</h3>
                        <p>100% SECURE PAYMENT</p>
                    </div>
                    <!-- .feature-content end -->
                </div>
            </div>
            <!-- .feature-panel end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #feature1 end -->

<!-- products #1
============================================= -->
<section id="products2" class="products pb-60 text-center">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="heading heading-2 mb-30">
                    <h2 class="heading--title">New Arrivals</h2>
                </div>
            </div>
            <!-- .col-lg-12 end -->
        </div>
        <!-- .row end -->
        <?php
        // Include the database connection file
        include("db_connection.php");

        // Fetch all product categories
        $sql = "SELECT * FROM productcategories";
        $result = mysqli_query($conn, $sql);
        $productCategories = mysqli_fetch_all($result, MYSQLI_ASSOC);

        ?>

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="products-tabs products-filter products-filter-2">
                    <ul class="nav nav-tabs justify-content-center" role="tablist">
                        <?php foreach ($productCategories as $index => $category) : ?>
                            <li>
                                <a href="#<?= strtolower(str_replace(' ', '_', $category['pcategory_name'])); ?>" aria-controls="<?= strtolower(str_replace(' ', '_', $category['pcategory_name'])); ?>" role="tab" data-toggle="tab" <?php if ($index === 0) : ?> class="active" <?php endif; ?>>
                                    <?= strtoupper($category['pcategory_name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="tab-content">
                        <?php foreach ($productCategories as $category) : ?>
                            <div role="tabpanel" class="tab-pane fade <?= $category['pcategory_id'] === $productCategories[0]['pcategory_id'] ? 'show active' : ''; ?>" id="<?= strtolower(str_replace(' ', '_', $category['pcategory_name'])); ?>">
                                <div class="row">
                                    <?php
                                    // Fetch products for the current category
                                    $sql = "SELECT * FROM products WHERE pcategory_id = {$category['pcategory_id']} ORDER BY product_id DESC LIMIT 4";
                                    $result = mysqli_query($conn, $sql);
                                    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

                                    // Initialize an array to keep track of unique product names for color variations
                                    $uniqueProductNames = array();

                                    // Iterate over each product in the category
                                    foreach ($products as $product) : ?>
                                        <!-- Product item -->
                                        <div class="col-sm-6 col-md-6 col-lg-3">
                                            <div class="product-item style2">
                                                <div class="product--img">
                                                <img src="<?= strpos($product['product_photo'], 'http') === 0 ? $product['product_photo'] : 'admin/' . $product['product_photo']; ?>" alt="<?= $product['product_name']; ?>" style="width: 300px; height: 300px; object-fit: cover;">
                                                    <?php if (isset($product['product_tag']) && !empty($product['product_tag'])) : ?>
                                                        <?php if ($product['product_tag'] === 'Sale' && isset($product['sale_start_date']) && isset($product['sale_end_date'])) : ?>
                                                            <?php
                                                            // Get the current date
                                                            $currentDate = date("Y-m-d");

                                                            // Check if the current date is within the sale period
                                                            if ($currentDate >= $product['sale_start_date'] && $currentDate <= $product['sale_end_date']) {
                                                                // If the current date is within the sale period, display the "Sale" tag
                                                            ?>
                                                                <span class="featured-item featured-item2"><?= $product['product_tag']; ?></span>
                                                            <?php } else { ?>
                                                                <!-- Add additional conditions or alternative display here -->
                                                            <?php } ?>
                                                        <?php else : ?>
                                                            <!-- Default behavior when product tag is not "Sale" -->
                                                            <span class="featured-item featured-item2"><?= $product['product_tag']; ?></span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>


                                                </div>

                                                <div class="product--content">
                                                    <div class="product--title">
                                                        <h3><a href=""><?= $product['product_name']; ?></a></h3>
                                                    </div>
                                                    <div class="product--price">
                                                        <?php if ($product['product_tag'] === 'Sale' && $currentDate >= $product['sale_start_date'] && $currentDate <= $product['sale_end_date']) : ?>
                                                            <div class="sale-wrapper">
                                                                <span class="original-price">$<?= $product['product_price']; ?></span>
                                                                <span class="sale-price">$<?= $product['product_sale_price']; ?></span>
                                                                <span class="sale-badge">Sale</span>
                                                            </div>
                                                        <?php else : ?>
                                                            <span>$<?= $product['product_price']; ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="product--hover">
                                                    <div class="product--action">
                                                        <?php
                                                        // Check if there are multiple products with the same name
                                                        $same_name_sql = "SELECT COUNT(*) AS count FROM products WHERE product_name = ?";
                                                        if ($stmt = mysqli_prepare($conn, $same_name_sql)) {
                                                            mysqli_stmt_bind_param($stmt, "s", $product['product_name']);
                                                            mysqli_stmt_execute($stmt);
                                                            mysqli_stmt_bind_result($stmt, $count);
                                                            mysqli_stmt_fetch($stmt);
                                                            mysqli_stmt_close($stmt);
                                                        }
                                                        ?>
                                                        <?php if ($count > 1) : ?>
                                                            <?php if ($product['product_stock_quantity'] > 0) : ?>
                                                                <a data-toggle="modal" data-target="#product-popup" class="btn btn--primary btn--rounded" data-product-id="<?= $product['product_id']; ?>"><i class="icon-bag"></i> ADD TO CART</a>
                                                            <?php else : ?>
                                                                <span class="btn btn--primary btn--rounded" style="cursor: not-allowed; opacity: 0.7;" disabled><i class="icon-bag"></i> OUT OF STOCK</span>
                                                            <?php endif; ?>
                                                        <?php else : ?>
                                                            <?php if ($product['product_stock_quantity'] > 0) : ?>
                                                                <a href="javascript:void(0);" class="btn btn--primary btn--rounded add-to-cart-index" data-product-id="<?= $product['product_id']; ?>"><i class="icon-bag"></i> ADD TO CART</a>
                                                            <?php else : ?>
                                                                <span class="btn btn--primary btn--rounded" style="cursor: not-allowed; opacity: 0.7;" disabled><i class="icon-bag"></i> OUT OF STOCK</span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>

                                                        <div class="product--action-content">
                                                            <div class="product--action-icons">
                                                                <a data-toggle="modal" data-target="#product-popup" data-product-id="<?= $product['product_id']; ?>"><i class="ti-search"></i></a>
                                                                <a class="add-to-wishlist" data-product-id="<?= $product['product_id']; ?>"><i class="ti-heart"></i></a>
                                                                <a data-toggle="modal" data-target="#compare-popup"><i class="ti-control-shuffle"></i></a>
                                                            </div>
                                                            <div class="product--hover-info">
                                                                <div class="product--title">
                                                                    <h3><a href="http://localhost/msport/product.php?id=<?= $product['product_id']; ?>" target="_blank"><?= $product['product_name']; ?></a></h3>
                                                                </div>
                                                                <div class="product--price">
                                                                    <?php if ($product['product_tag'] === 'Sale' && $currentDate >= $product['sale_start_date'] && $currentDate <= $product['sale_end_date']) : ?>
                                                                        <span class="original-price" style="text-decoration: line-through;">$<?php echo $product['product_price']; ?></span>
                                                                        <span class="sale-price">$<?php echo $product['product_sale_price']; ?></span>
                                                                    <?php else : ?>
                                                                        <span>$<?php echo $product['product_price']; ?></span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="category--colors">
                                                                <?php
                                                                // Display color boxes for all products with the same name
                                                                if (!in_array($product['product_name'], $uniqueProductNames)) {
                                                                    $keywords = explode(",", $product['product_keywords']);
                                                                    $color_keywords = array("red", "blue", "green", "yellow", "black", "white");

                                                                    // Prepare the SQL statement
                                                                    $same_name_sql = "SELECT * FROM products WHERE product_name = ?";

                                                                    // Initialize an array to store color variations for the current product
                                                                    $product_colors = array();

                                                                    // Prepare and execute the statement
                                                                    if ($stmt = mysqli_prepare($conn, $same_name_sql)) {
                                                                        // Bind the parameter
                                                                        mysqli_stmt_bind_param($stmt, "s", $product['product_name']);

                                                                        // Execute the statement
                                                                        mysqli_stmt_execute($stmt);

                                                                        // Get the result set
                                                                        $same_name_result = mysqli_stmt_get_result($stmt);

                                                                        // Fetch rows
                                                                        while ($same_name_row = mysqli_fetch_assoc($same_name_result)) {
                                                                            $same_name_keywords = explode(",", $same_name_row['product_keywords']);
                                                                            foreach ($same_name_keywords as $same_name_keyword) {
                                                                                foreach ($color_keywords as $color) {
                                                                                    if (stripos($same_name_keyword, $color) !== false) {
                                                                                        // Store color variations along with product IDs in an array
                                                                                        $product_colors[$same_name_row['product_id']][] = strtolower($color);
                                                                                    }
                                                                                }
                                                                            }
                                                                        }

                                                                        // Close the statement
                                                                        mysqli_stmt_close($stmt);
                                                                    }

                                                                    // Output color boxes
                                                                    foreach ($product_colors as $product_id => $colors) {
                                                                        foreach ($colors as $color) {
                                                                            echo '<div class="color-box circular" style="background-color: ' . $color . ';" data-product-id="' . $product_id . '" data-toggle="modal" data-target="#product-popup"></div>';
                                                                            echo '<div class="product-photo-popup" style="display: none;"></div>';
                                                                        }
                                                                    }

                                                                    // Add the product name to the unique product names array
                                                                    $uniqueProductNames[] = $product['product_name'];
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Product item -->
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>




                </div>
            </div>
        </div>
    </div>
    <!-- .container end -->
</section>
<!-- #products end -->

<!-- banner-img #3
============================================= -->
<section id="banner-img" class="banner-img pt-0 pb-0">
    <div class="container-fluid pr-40 pl-40 pr-15-xs pl-15-xs">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="banner--img">
                    <a href="#">
                        <img src="assets/images/banner-img/4.jpg" alt="banner-img" class="img-fluid">
                    </a>
                </div>
            </div>
            <!-- .col-lg-12 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #banner-img end -->


<!-- Testimonial #1
============================================= -->
<section id="testimonial1" class="testimonial testimonial-1 text-center pt-150 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-8 offset-lg-2">
                <div class="carousel owl-carousel carousel-navs" data-slide="1" data-slide-rs="1" data-autoplay="false" data-nav="true" data-dots="false" data-space="0" data-loop="true" data-speed="800">
                    <!-- Testimonial panel #1 -->
                    <div class="testimonial-panel">
                        <div class="testimonial--meta-img">
                            <img src="assets/images/testimonials/authors/2.jpg" alt="author">
                        </div>
                        <!-- .testimonial-meta-img end -->
                        <div class="testimonial--body">
                            <p>“ My project was a simple & small task, but the persistence and determination of
                                the team turned it into an awesome and great project which make me very happy! ”
                            </p>
                        </div>
                        <!-- .testimonial-body end -->
                        <div class="testimonial--meta">
                            <h4>JONT HENRY</h4>
                            <span>CEO Zytheme</span>
                        </div>
                        <!-- .testimonial-meta end -->
                    </div>
                    <!-- .testimonial-panel end -->

                    <!-- Testimonial panel #2 -->
                    <div class="testimonial-panel">
                        <div class="testimonial--meta-img">
                            <img src="assets/images/testimonials/authors/3.jpg" alt="author">
                        </div>
                        <!-- .testimonial-meta-img end -->
                        <div class="testimonial--body">
                            <p>“ My project was a simple & small task, but the persistence and determination of
                                the team turned it into an awesome and great project which make me very happy! ”
                            </p>
                        </div>
                        <!-- .testimonial-body end -->
                        <div class="testimonial--meta">
                            <h4>JOHn DOE</h4>
                            <span>FOUNDER</span>
                        </div>
                        <!-- .testimonial-meta end -->
                    </div>
                    <!-- .testimonial-panel end -->
                </div>
            </div>
            <!-- .col-lg-8 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #testimonial1 end -->


<!-- Clients #1
============================================= -->
<section id="clients1" class="clients clients-1 text-center pt-0 pb-0">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="carousel  owl-carousel" data-slide="6" data-slide-rs="2" data-autoplay="true" data-nav="false" data-dots="false" data-space="0" data-loop="true" data-speed="800">
                    <div class="client">
                        <img src="assets/images/clients/1.png" alt="client">
                    </div>
                    <div class="client">
                        <img src="assets/images/clients/2.png" alt="client">
                    </div>
                    <div class="client">
                        <img src="assets/images/clients/3.png" alt="client">
                    </div>
                    <div class="client">
                        <img src="assets/images/clients/4.png" alt="client">
                    </div>
                    <div class="client">
                        <img src="assets/images/clients/5.png" alt="client">
                    </div>
                    <div class="client">
                        <img src="assets/images/clients/6.png" alt="client">
                    </div>
                </div>
            </div>
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #clients1 end -->

<!-- instagram #2
============================================= -->
<section id="instagram2" class="instagram instagram-2 pb-0">
    <div class="container-fluid pr-40 pl-40">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6 offset-lg-3">
                <div class="heading mb-100 text-center">
                    <h2 class="heading--title">From Instagram</h2>
                    <div class="heading--icon"><i class="fa fa-instagram"></i><a href="#">@zytheme.com</a></div>
                    <p class="heading--desc">STYLISH, DESIGNER, INTERACTIVE, SIMPLIFIED AND PERSONAL TOUCH TO
                        INTEROR DESIGN</p>
                </div>
            </div>
            <!-- .col-lg-6 end -->
        </div>
        <!-- .row end -->
        <div class="row row-no-padding">
            <!-- instagarm img #1 -->
            <div class="col">
                <div class="instagram--img">
                    <div class="img--hover"></div>
                    <img src="assets/images/instagram/1.jpg" alt="img" class="img-fluid">
                </div>
            </div>
            <!-- .col-md-5ths end -->
            <!-- instagarm img #2 -->
            <div class="col">
                <div class="instagram--img">
                    <div class="img--hover"></div>
                    <img src="assets/images/instagram/2.jpg" alt="img" class="img-fluid">
                </div>
            </div>
            <!-- .col-md-5ths end -->
            <!-- instagarm img #3 -->
            <div class="col">
                <div class="instagram--img">
                    <div class="img--hover"></div>
                    <img src="assets/images/instagram/3.jpg" alt="img" class="img-fluid">
                </div>
            </div>
            <!-- .col-md-5ths end -->
            <!-- instagarm img #4 -->
            <div class="col">
                <div class="instagram--img">
                    <div class="img--hover"></div>
                    <img src="assets/images/instagram/4.jpg" alt="img" class="img-fluid">
                </div>
            </div>
            <!-- .col-md-5ths end -->
            <!-- instagarm img #5 -->
            <div class="col">
                <div class="instagram--img">
                    <div class="img--hover"></div>
                    <img src="assets/images/instagram/5.jpg" alt="img" class="img-fluid">
                </div>
            </div>
            <!-- .col-md-5ths end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #instagram end -->
<!-- Footer #2
============================================= -->
<?php
include("footer.php");
?>