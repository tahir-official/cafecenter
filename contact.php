    <?php
      include_once('include/header.php');
    ?>

    <div class="container">
      <div class="page-banner">
        <div class="row justify-content-center align-items-center h-100">
          <div class="col-md-6">
            <nav aria-label="Breadcrumb">
              <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                <li class="breadcrumb-item"><a href="<?=$site_url?>">Home</a></li>
                <li class="breadcrumb-item active">Contact</li>
              </ul>
            </nav>
            <h1 class="text-center">Contact Us</h1>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="page-section">
    <div class="container">
      <div class="row text-center align-items-center">
        <div class="col-lg-4 py-3">
          <div class="display-4 text-center text-primary"><span class="mai-planet"></span></div>
          <p class="mb-3 font-weight-medium text-lg">Web</p>
          <p class="mb-0 text-secondary"><a href="<?=$portal_detail->WEBSITE?>" class="text-secondary"><?=$portal_detail->WEBSITE?></a></p>
        </div>
        <div class="col-lg-4 py-3">
          <div class="display-4 text-center text-primary"><span class="mai-call"></span></div>
          <p class="mb-3 font-weight-medium text-lg">Phone</p>
          <p class="mb-0"><a href="tel:<?=$portal_detail->SUPPORT_NUMBER?>" class="text-secondary"><?=$portal_detail->SUPPORT_NUMBER?></a></p>
          
        </div>  
        <div class="col-lg-4 py-3">
          <div class="display-4 text-center text-primary"><span class="mai-mail"></span></div>
          <p class="mb-3 font-weight-medium text-lg">Email Address</p>
          <p class="mb-0"><a href="mailto:<?=$portal_detail->SUPPORT_EMAIL?>" class="text-secondary"><?=$portal_detail->SUPPORT_EMAIL?></a></p>
          
        </div>
      </div>
    </div>

    <div class="container-fluid mt-4">
      <div class="row">
        <div class="col-lg-12 mb-5 mb-lg-0">
          <form  class="contact-form py-5 px-lg-5" method="post" id="contactFrom">
            <h2 class="mb-4 font-weight-medium text-secondary">Get in touch</h2>
            <div id="alert" ></div>
            <div class="row form-group">
              <div class="col-md-12">
                <label class="text-black" for="full_name">Name</label>
                <input type="text" id="full_name" name="full_name"  class="form-control">
              </div>
              
            </div>
    
            <div class="row form-group">
              <div class="col-md-12">
                <label class="text-black" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control">
              </div>
            </div>
    
            <div class="row form-group">
    
              <div class="col-md-12">
                <label class="text-black" for="subject">Subject</label>
                <input type="text" id="subject" name="subject" class="form-control">
              </div>
            </div>
    
            <div class="row form-group">
              <div class="col-md-12">
                <label class="text-black" for="message">Message</label>
                <textarea name="message" id="message" name="message" cols="30" rows="5" class="form-control" placeholder="Write your notes or questions here..."></textarea>
              </div>
            </div>
    
            <div class="row form-group mt-4">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary btnContact">Send Message</button>
              </div>
            </div>
          </form>
        </div>
        
      </div>
    </div>
  </div>
 
  <?php
      include_once('include/footer.php');
  ?>