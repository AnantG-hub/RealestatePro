<?php
session_start();
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

include "header.php";
include "db.php";

// Sanitize inputs
$location = strtolower(trim($_GET['location'] ?? ''));
$type = $_GET['type'] ?? '';
$offer = $_GET['offer'] ?? '';

$conditions = [];

if (!empty($location)) {
    $conditions[] = "LOWER(location) LIKE '%" . mysqli_real_escape_string($conn, $location) . "%'";
}
if (!empty($type)) {
    $conditions[] = "type = '" . mysqli_real_escape_string($conn, $type) . "'";
}
if (!empty($offer)) {
    $conditions[] = "offer = '" . mysqli_real_escape_string($conn, $offer) . "'";
}

$whereClause = implode(' AND ', $conditions);
$query = "SELECT * FROM properties";
if (!empty($whereClause)) {
    $query .= " WHERE $whereClause";
}
$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html lang="en">  
  <link id="themeStylesheet" rel="stylesheet" href="css/light-theme.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Featured Properties</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/medium-zoom@1.0.6/dist/medium-zoom.css">
<script src="https://unpkg.com/medium-zoom@1.0.6/dist/medium-zoom.min.js"></script>
<link rel="stylesheet" href="nav2.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-DY5VYtO1Xm+8SL9ZrR3ztUtZqOqkxLfT8ChP+XELKrjQZ5MaDnIFHzFYN/uzDOnnCMOv9cQ9RUKIilq1w7Zb8A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div class="container">
  <h1 class="text-center my-4">Featured <span class="text-primary">Properties</span></h1>
  <div class="filter-bar text-center mb-4">
    <button class="btn btn-outline-dark active" data-filter="all">All</button>
    <button class="btn btn-outline-primary" data-filter="forrent">For Rent</button>
    <button class="btn btn-outline-success" data-filter="forsell">For Sell</button>
    <a href="add_property.php" class="btn btn-outline-danger">+ Add Property</a>
  </div>
  <div class="property-grid">
    <?php
    $query = "SELECT * FROM properties ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
      $internal_images = array_filter(explode(',', $row['internal_images']));
      $internal_images = array_map('trim', $internal_images);
      $gallery_images = array_filter(explode(',', $row['gallery_images']));
      $gallery_images = array_map('trim', $gallery_images);
      $all_images = array_merge($internal_images, $gallery_images);
      $filterClass = strtolower(str_replace(' ', '', $row['type']));
    ?>
    <div class="card mb-5 filterDiv <?php echo $filterClass; ?>">
      <img src="uploads/<?php echo $row['image']; ?>" class="card-img-top" alt="Main Image">
      <div class="card-body">
        <h4 class="card-title">📌 <?php echo $row['title']; ?></h4>
        <p class="card-text">
          <strong>📍 Location:</strong> <?php echo $row['location']; ?><br>
          <strong>💰 Price:</strong> ₹<?php echo $row['price']; ?><br>
          <strong>📐 Area:</strong> <?php echo $row['area']; ?> sq ft<br>
          <strong>🛌 Beds:</strong> <?php echo $row['beds']; ?> | <strong>🚱 Baths:</strong> <?php echo $row['baths']; ?>
        </p>

        <?php if (!empty($row['video_link'])): ?>
          <p><strong>🎥 Video Link:</strong> <a href="<?php echo $row['video_link']; ?>" target="_blank">Watch Tour</a></p>
        <?php endif; ?>

        <?php if (!empty($row['plan_image'])): ?>
          <div class="mt-3">
            <strong>🗘️ Plan Image:</strong><br>
            <img src="uploads/<?php echo $row['plan_image']; ?>" alt="Plan Image" class="big-img zoomable">
          </div>
        <?php endif; ?>

        <?php if (!empty($all_images)): ?>
          <div class="mt-3">
            <strong>🖼️ Gallery Images:</strong>
            <div class="custom-slider-wrapper">
              <button onclick="slideLeft('<?php echo $row['id']; ?>')">❮</button>
              <div class="custom-slider-container">
                <div class="custom-slider" id="slider-<?php echo $row['id']; ?>">
                  <?php foreach ($all_images as $img): ?>
                    <img src="uploads/<?php echo $img; ?>" class="slide-img zoomable" alt="Image">
                  <?php endforeach; ?>
                </div>
              </div>
              <button onclick="slideRight('<?php echo $row['id']; ?>')">❯</button>
            </div>
          </div>
      <?php endif; ?>
      </div>
      <!-- These buttons only show for admin -->
<?php if (isset($_SESSION['username'])): ?>
<div class="edit-icons mt-3">
    <button class="btn btn-outline-secondary" onclick="editWithCode(<?php echo $row['id']; ?>)">
    <i class="fas fa-edit"></i> Edit
</button>
    <button class="btn btn-outline-danger" onclick="confirmDelete(<?php echo $row['id']; ?>)">
        <i class="fas fa-trash"></i> Delete
    </button>
</div>
<?php endif; ?>
    </div>
    <?php } ?>
  </div>
</div>


<br> <br>


<!-- About Section -->
<section class="about">
    <h2 class="heading">About <span class="highlight">REMS</span></h2>
   <div class="row">
      <div class="image">
         <img src="about-img.svg" alt="About Image">
      </div>
      <div class="content">
         <h3>Why Choose Us?</h3>
         <p>The reach of the World Wide Web has expanded into millions of households, making the Internet the most powerful platform for real estate marketing today. This project bridges multiple domains, from business logic to computing, requiring deep research to meet real-world needs.</p>
         <a href="contact.php" class="inline-btn">Contact Us</a>
      </div>
          <div class="popup-content">
          <h2 style="color: green;">REMS Essential Details..</h2>
            <p>Real Estate is a management of realestate
                agencies,agents,clients and financialtransactions,
                REMS provides comprehensive 
                reports for managing the Real Estate agency performance and efficiency,and
                enables the management for a better decision-making</p>
          
      </div>
   </div>
</section>

<!-- Steps Section -->
<section class="steps">
   <h2 class="heading"><span class="highlight">3 Simple Steps</span></h2>
   <div class="box-container">
      <div class="box">
         <img src="step-1.png" alt="">
         <h3>Search Property</h3>
         <p>Our web platform is available 24/7, ensuring you can search properties anytime.</p>
      </div>
      <div class="box">
         <img src="step-2.png" alt="">
         <h3>Contact Agents</h3>
         <p>Both customers and internal staff can use the system seamlessly and efficiently.</p>
      </div>
      <div class="box">
         <img src="step-3.png" alt="">
         <h3>Enjoy Property</h3>
         <p>Our platform saves you time and money by simplifying your property journey.</p>
      </div>
   </div>
</section>

<!-- Agent Section -->
<section class="Agent" id="Agent">
   <h2 class="heading">Our <span class="highlight">Commercial Agents</span></h2>
   <div class="boxx">
      <div class="box">
         <img src="agent1.png" alt="">
         <h2><u>Realtor Bios</u></h2>
         <h3><i class="fa fa-quote-left"></i> Awesome Services! <i class="fa fa-quote-right"></i></h3>
         <p>The master-builder of human happiness no one rejects, dislikes, or avoids pleasure itself.</p>
         <div class="app-icon">
            <i class="fab fa-facebook-f"></i>
<i class="fab fa-twitter"></i>
<i class="fab fa-whatsapp"></i>
<i class="fab fa-instagram"></i>
<i class="fab fa-linkedin-in"></i>
<i class="fab fa-telegram"></i>

         </div>
      </div>
      <div class="box">
         <img src="agent2.png" alt="">
         <h2><u>John Wills</u></h2>
         <h3><i class="fa fa-quote-left"></i> Great & Talented Team! <i class="fa fa-quote-right"></i></h3>
         <p>They delivered my project on time with a highly skilled and professional team.</p>
         <div class="app-icon">
           <i class="fab fa-facebook-f"></i>
<i class="fab fa-twitter"></i>
<i class="fab fa-whatsapp"></i>
<i class="fab fa-instagram"></i>
<i class="fab fa-linkedin-in"></i>
<i class="fab fa-telegram"></i>

         </div>
      </div>
      <div class="box">
         <img src="agent3.png" alt="">
         <h2><u>Emily Willim</u></h2>
         <h3><i class="fa fa-quote-left"></i> Wonderful Support! <i class="fa fa-quote-right"></i></h3>
         <p>The master-builder of human happiness no one rejects, dislikes avoids pleasure itself.</p>
         <div class="app-icon">
        <i class="fab fa-facebook-f"></i>
<i class="fab fa-twitter"></i>
<i class="fab fa-whatsapp"></i>
<i class="fab fa-instagram"></i>
<i class="fab fa-linkedin-in"></i>
<i class="fab fa-telegram"></i>

         </div>
      </div>
      <div class="box">
         <img src="agent4.jpg" alt="">
         <h2><u>Rose Denze</u></h2>
         <h3><i class="fa fa-quote-left"></i> Awesome Services! <i class="fa fa-quote-right"></i></h3>
         <p>Explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you completed.</p>
         <div class="app-icon">
           <i class="fab fa-facebook-f"></i>
<i class="fab fa-twitter"></i>
<i class="fab fa-whatsapp"></i>
<i class="fab fa-instagram"></i>
<i class="fab fa-linkedin-in"></i>
<i class="fab fa-telegram"></i>

         </div>
      </div>
   </div>
</section>

<br><br>
<!-- Modal for Service Details -->
<!-- Services Section -->
<section class="Services" id="Services">
  <h2 class="heading">Our <span class="highlight">Services</span></h2>
  <div class="boxo">
    <div class="box">
      <img src="icon-02.png" alt="">
      <h3>Buying homes...</h3>
      <p>Discover your dream property with expert support.</p>
    <a href="#" class="btn learn-btn" onclick="toggleService('buy')">Learn More</a>


    <div class="box">
      <img src="icon-03.png">
      <h3>Rent homes...</h3>
      <p>Find the perfect rental property easily.</p>
          <a href="#" class="btn learn-btn" onclick="toggleService('rent')">Learn More</a>
    </div>

    <div class="box">
      <img src="icon-01.png" alt="">
      <h3>Selling homes...</h3>
      <p>Let us help you sell your property at best value.</p>
          <a href="#" class="btn learn-btn" onclick="toggleService('sell')">Learn More</a>
    </div>

    <div class="box">
      <img src="24hour.png" alt="">
      <h3>24/7 Services...</h3>
      <p>We are here for you, anytime, anywhere.</p>
          <a href="#" class="btn learn-btn" onclick="toggleService('hours')">Learn More</a>
    </div>

    <div class="box">
      <img src="shop.png" alt="">
      <h3>Shops and Malls...</h3>
      <p>Complete mall and retail services under one roof.</p>
          <a href="#" class="btn learn-btn" onclick="toggleService('mall')">Learn More</a>
    </div>
  </div>


<!-- Full Service Sections (with IDs for matching) -->
<div id="buy" class="buy">
    <h2>Buying Services</h2>
    <p>Our services help maximize asset value and guide you at every step. Whether you're a seasoned homeowner, first-time buyer, or commercial investor, our tailored advice and deep market insights ensure the best results. We support from opportunity sourcing to due diligence.</p>
  </div>



<div id="rent" class="rent">
    <h2>Rent Services</h2>
    <p>Post rental properties for free on realestate.com. Provide full details like configuration, furnishing, parking, deposit, tenant preference, etc. Use real photos for better reach and response.</p>
  </div>



<div id="sell" class="sell">
  <h2>Selling</h2>
    <p>Maximize your asset’s value with expert guidance. We serve industrial, logistics, and student accommodation markets with consulting, M&A, financing, valuation, and technical know-how.</p>
  </div>

<div id="hours" class="hours">
    <h2>24hours Services</h2>
    <p>Real estate activity happens 24/7. We offer 24-hour answering services to never miss a lead. Our bilingual staff ensure accurate message handling and client follow-up so you never lose a sale opportunity.</p>
  </div>



<div id="mall" class="mall">
    <h2>Shops & Malls Services</h2>
    <p>The Mall Company manages mall development, branding, and operations. We work with architects and stakeholders to ensure your mall thrives with great design, loyal customers, and strong marketing.</p>
  </div>


<!-- JS Script - placed at end for DOM access -->
<script>
 function showDetails(sectionId) {
  const section = document.getElementById(sectionId);
  if (section) {
    const heading = section.querySelector('h2')?.innerText || 'Service Info';
    const paragraph = section.querySelector('p')?.innerText || 'No description available.';

    document.getElementById('modalTitle').innerText = heading;
    document.getElementById('modalText').innerText = paragraph;
    document.getElementById('infoModal').style.display = 'block';
  } else {
    alert('Details not found.');
  }
}

</script>
   </section>

<!-- Client Reviews Section -->
<section class="reviews" id="reviews">
   <h2 class="heading">Client's<span class="highlight"> Reviews</span></h2>
   <div class="reviews box-container">
      <?php
         $result = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC");
         while ($row = $result->fetch_assoc()):
      ?>
      <div class="box">
         <div class="user">
            <span><?= htmlspecialchars($row['username']) ?></span>
            <div class="stars">
               <?php
                  for ($i = 1; $i <= 5; $i++) {
                     echo $i <= $row['stars'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                  }
               ?>
            </div>
         </div>
         <p><?= htmlspecialchars($row['comment']) ?></p>
      </div>
      <?php endwhile; ?>
   </div>
</section>
<!-- Submit Review Section -->

   <section class="submit-review">
      <h2 class="heading">Leave a <span class="highlight">Review</span></h2>
      <form method="post" class="review-form" style="max-width:600px;margin:auto;">
         <label>Stars (1 to 5):</label>
         <select name="stars" required>
            <option value="5">★★★★★</option>
            <option value="4">★★★★</option>
            <option value="3">★★★</option>
            <option value="2">★★</option>
            <option value="1">★</option>
         </select>
         <br><br>
         <label>Your Comment:</label>
         <textarea name="comment" rows="4" required></textarea>
         <br><br>
         <button type="submit" name="submit_review" class="inline-btn">Submit Review</button>
      </form>
    <?php
        if (isset($_POST['submit_review'])) {
          $stars = intval($_POST['stars']);
          $comment = $conn->real_escape_string($_POST['comment']);
          $username = $_SESSION['username'];
  
          if ($stars >= 1 && $stars <= 5 && !empty($comment)) {
              $conn->query("INSERT INTO reviews (username, stars, comment) VALUES ('$username', $stars, '$comment')");
              echo "<script>alert('Review submitted successfully!');</script>";
              echo "<script>window.location.reload();</script>";
          } else {
              echo "<script>alert('Invalid input. Please try again.');</script>";
          }
        }
    ?>
</section>
<!-- Contact Section -->
<section class="contact" id="contact">
    <h1 class="heading"><span class="highlight">Contact Form</span> Us</h1>
    <section class="form" id="form">
        <div class="box">
            <form action="serverr.php" method="post" autocomplete="off">
                <h2>Send a Message..</h2>
                <div class="inputbox">
                    <span>First Name :</span>
                    <input type="text" id="first_name" name="first_name" placeholder="First..." required>
                </div>
                <div class="inputbox">
                    <span>Last Name :</span>
                    <input type="text" id="last_name" name="last_name" placeholder="Last..." required>
                </div>
                <div class="inputbox">
                    <span>Email :</span>
                    <input type="text" id="email" name="email" placeholder="@gmail.com" required>
                </div>
                <div class="inputbox">
                    <span>Mobile No. :</span>
                    <input type="text" id="phone" name="phone" placeholder="+91-" required>
                </div>
                <div class="inputbox">
                    <span>Message :</span>
                    <textarea name="Message" placeholder="Write something here..." cols="40" rows="6" id="comments"></textarea>
                </div>
                <div class="inputbox">
                    <input type="submit" name="submit" value="Send Message" class="btn">
                </div>
            </form>
        </div>
    </section><div class="map-container" style="margin-top: 8rem; border-radius: 10px; overflow: hidden;">
                <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3440.2081643617226!2d78.00383521511918!3d30.332607707685934!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390929fbceac6c27%3A0x9c5c12a59400d9b9!2sNanda%20Ki%20Chowki%2C%20Dehradun%2C%20Uttarakhand%20248007!5e0!3m2!1sen!2sin!4v1658417299443!5m2!1sen!2sin" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
</section>

<!-- Footer -->
<?php include "footer.php"; ?>
<script src="filterr.js"></script>
<script>
let sliderIndex = {};
function slideLeft(id) {
  const slider = document.getElementById("slider-" + id);
  const slide = slider.querySelector(".slide-img");
  if (!slide) return;
  const gap = parseInt(getComputedStyle(slider).gap) || 10;
  const step = slide.clientWidth + gap;
  if (!sliderIndex[id]) sliderIndex[id] = 0;
  sliderIndex[id] = Math.max(sliderIndex[id] - 1, 0);
  slider.style.transform = `translateX(-${sliderIndex[id] * step}px)`;
}
function slideRight(id) {
  const slider = document.getElementById("slider-" + id);
  const slide = slider.querySelector(".slide-img");
  if (!slide) return;
  const gap = parseInt(getComputedStyle(slider).gap) || 10;
  const step = slide.clientWidth + gap;
  const total = slider.children.length;
  if (!sliderIndex[id]) sliderIndex[id] = 0;
  sliderIndex[id] = Math.min(sliderIndex[id] + 1, total - 1);
  slider.style.transform = `translateX(-${sliderIndex[id] * step}px)`;
}
function confirmDelete(id) {
  const code = prompt("🔐 Enter Delete Code:");
  if (code === "2002") {
    window.location.href = "delete_property.php?id=" + id + "&code=" + code;
  } else {
    alert("❌ Wrong Delete Code");
  }
}
function setActiveButton(event) {
  const btns = document.querySelectorAll(".filter-bar .btn");
  btns.forEach(btn => btn.classList.remove("active"));
  event.target.classList.add("active");
}
document.querySelectorAll('.filter-bar .btn').forEach(btn => {
  btn.addEventListener('click', function(event) {
    const category = this.getAttribute("data-filter");
    filterSelection(category);
    setActiveButton(event);
  });
});
function filterSelection(category) {
  const cards = document.querySelectorAll(".filterDiv");
  cards.forEach(card => {
    card.style.display = (category === "all" || card.classList.contains(category)) ? "block" : "none";
  });
}
document.addEventListener("DOMContentLoaded", function() {
  filterSelection('all');
  mediumZoom('.zoomable');
});
</script>
<script>
function confirmDelete(id) {
    const code = prompt("🗑️ Enter Delete Code to proceed:");
    if (!code || code.trim() === "") {
        alert("❗Cancelled");
        return;
    }
    window.location.href = "delete_property.php?id=" + id + "&code=" + encodeURIComponent(code);
}

function editWithCode(id) {
    const code = prompt("✏️ Enter Edit Code to proceed:");
    if (!code || code.trim() === "") {
        alert("❗Cancelled");
        return;
    }

    // Example: code is passed via query string
    window.location.href = "edit_property.php?id=" + id + "&code=" + encodeURIComponent(code);
}
</script>

<script>

function toggleService(serviceId) {
  const services = ['buy', 'rent', 'sell', 'hours', 'mall'];

  services.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.classList.remove('active');
  });

  const selected = document.getElementById(serviceId);
  if (selected) selected.classList.add('active');

  // Optional: scroll to that section smoothly
  if (selected) {
    selected.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body> 
</html>