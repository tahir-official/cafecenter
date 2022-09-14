 <style>
/*image box*/
.container_img_box {
width: 100px;
height: 100px;
display: block;
margin: 0 auto;
}

.outer {
width: 100% !important;
height: 100% !important;
max-width: 150px !important; /* any size */
max-height: 150px !important; /* any size */
margin: auto;
border-radius: 100%;
position: relative;
background-position: center;
background-repeat: no-repeat;
background-size: cover;
}
    
.inner {
  background-color: #6c55f9;
  width: 30px;
  height: 30px;
  border-radius: 100%;
  position: absolute;
  bottom: 0;
  right: 0;
}

.inner:hover {
background-color: #5555ff;
}
.inputfile {
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: 1;
    width: 50px;
    height: 50px;
}
.inputfile + label {
text-overflow: ellipsis;
white-space: nowrap;
display: inline-block;
overflow: hidden;
width: 30px;
height: 30px;
pointer-events: none;
cursor: pointer;
line-height: 20px;
text-align: center;
}
.inputfile + label svg {
    fill: #fff;
}
/*image box*/
/*css for subcraption*/
.panel
{
    text-align: center;
}
.panel:hover { box-shadow: 0 1px 5px rgba(0, 0, 0, 0.4), 0 1px 5px rgba(130, 130, 130, 0.35); }
.panel-body
{
    padding: 0px;
    text-align: center;
}

.the-price
{
    background-color: rgba(220,220,220,.17);
    box-shadow: 0 1px 0 #dcdcdc, inset 0 1px 0 #fff;
    padding: 20px;
    margin: 0;
}

.the-price h1
{
    line-height: 1em;
    padding: 0;
    margin: 0;
}

.subscript
{
    font-size: 25px;
}

/* CSS-only ribbon styles    */
.cnrflash
{
    /*Position correctly within container*/
    position: absolute;
    top: -9px;
    right: 4px;
    z-index: 1; /*Set overflow to hidden, to mask inner square*/
    overflow: hidden; /*Set size and add subtle rounding  		to soften edges*/
    width: 100px;
    height: 100px;
    border-radius: 3px 5px 3px 0;
}
.cnrflash-inner
{
    /*Set position, make larger then 			container and rotate 45 degrees*/
    position: absolute;
    bottom: 0;
    right: 0;
    width: 145px;
    height: 145px;
    -ms-transform: rotate(45deg); /* IE 9 */
    -o-transform: rotate(45deg); /* Opera */
    -moz-transform: rotate(45deg); /* Firefox */
    -webkit-transform: rotate(45deg); /* Safari and Chrome */
    -webkit-transform-origin: 100% 100%; /*Purely decorative effects to add texture and stuff*/ /* Safari and Chrome */
    -ms-transform-origin: 100% 100%;  /* IE 9 */
    -o-transform-origin: 100% 100%; /* Opera */
    -moz-transform-origin: 100% 100%; /* Firefox */
    background-image: linear-gradient(90deg, transparent 50%, rgba(255,255,255,.1) 50%), linear-gradient(0deg, transparent 0%, rgba(1,1,1,.2) 50%);
    background-size: 4px,auto, auto,auto;
    background-color: #aa0101;
    box-shadow: 0 3px 3px 0 rgba(1,1,1,.5), 0 1px 0 0 rgba(1,1,1,.5), inset 0 -1px 8px 0 rgba(255,255,255,.3), inset 0 -1px 0 0 rgba(255,255,255,.2);
}
.cnrflash-inner:before, .cnrflash-inner:after
{
    /*Use the border triangle trick to make  				it look like the ribbon wraps round it's 				container*/
    content: " ";
    display: block;
    position: absolute;
    bottom: -16px;
    width: 0;
    height: 0;
    border: 8px solid #800000;
}
.cnrflash-inner:before
{
    left: 1px;
    border-bottom-color: transparent;
    border-right-color: transparent;
}
.cnrflash-inner:after
{
    right: 0;
    border-bottom-color: transparent;
    border-left-color: transparent;
}
.cnrflash-label
{
    /*Make the label look nice*/
    position: absolute;
    bottom: 0;
    left: 0;
    display: block;
    width: 100%;
    padding-bottom: 5px;
    color: #fff;
    text-shadow: 0 1px 1px rgba(1,1,1,.8);
    font-size: 0.95em;
    font-weight: bold;
    text-align: center;
}
</style>
 <div class="left_side">
    <div class="profile_box1" style="background: url(assets/img/dashboard.png);background-repeat: no-repeat;background-position: center;background-size: cover;">
        
            <form  name="profile_form" id="profile_form" method="post" enctype="multipart/form-data">
                <div class="container_img_box">
                    <div class="outer" style="background-image: url(<?=$user_data->profile?>);">
                        <div class="inner">
                        
                        <input class="inputfile" type="file" name="profile_image" id="profile_image" accept="image/*">
                        
                        <label><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg></label>
                        </div>
                    </div>
                </div>
            </form>
            <div class="loader loader--style1" title="0" style="display: none;text-align: center;">
                <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                width="40px" height="40px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
                <path opacity="0.2" fill="blue" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
                s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
                c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/>
                <path fill="red" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
                C22.32,8.481,24.301,9.057,26.013,10.047z">
                <animateTransform attributeType="xml"
                    attributeName="transform"
                    type="rotate"
                    from="0 20 20"
                    to="360 20 20"
                    dur="0.5s"
                    repeatCount="indefinite"/>
                </path>
                </svg>
            </div>
        
        <h4 id="profile_name"><?=ucfirst($user_data->fname).' '.ucfirst($user_data->lname)?></h4>
        
    </div>
    <ul class="ul_set sider_barr">
        <li><a href="dashboard.php"><span class="mai-school-outline"></span> Dashboard</a></li>
        <li><a href="profile.php"><span class="mai-person-circle-outline"></span> Profile</a></li>
        <li><a href="subscription-plan.php"><span class="mai-sync-circle-outline"></span> Subscription Plan</a></li>
        <li><a href="wallet.php"><span class="mai-wallet-outline" ></span> Wallet Management <span class="notification_count" id="notification_count"><?=$portal_detail->CURRENCY.' '.$user_data->wallet?></span></a> </li>
        <li><a href="recharge.php"><span class="mai-card-outline"></span> Recharge Management</a></li>
        <li><a href=""><span class="mai-people-circle-outline"></span> Consumer Management</a></li>
        <!-- <li><a href=""><span class="mai-logo-blogger"></span> Blog</a></li>
        <li><a href=""><span class="mai-book-outline"></span> Template Management</a></li> -->
        <li><a href="change-password.php"><span class="mai-settings-outline"></span> Change Password</a></li>
        <li><a href="logout.php"><span class="mai-power"></span> Log Out</a></li>
    </ul>
</div>