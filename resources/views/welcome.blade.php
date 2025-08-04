<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Al Khinjar Al Dhahbi Antiques</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montaga&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<style>
    .hero-img {
    width: 100%;
    height: 100vh;
    object-position: center;
    justify-self: center;
    align-self: baseline;
}

.hero-container {
    width: 100%;
    height: 100vh;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin-top: 50px;
}

.hero-text {
    /* width: 50%; */
    text-align: center;
    color: #4b3621;
}

.about-us-container {
    display: flex;
    width: 95%;
    justify-content: space-evenly;
    align-items: center;
    justify-self: center;
}

.about-us-imgs {
    width: 40%;
    height: 300px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

.about-us-img {
    width: 33%;
    height: 100%;

    /* box-shadow: 0 6px 10px #000; */
    object-fit: cover;

}



.about-text-container {
    width: 40%;
    color: #4b3621;
}

.contact-container{
    display: flex;
    width: 50%;
    justify-self: center;
    margin-top: 100px;
    margin-bottom: 50px;
    border-radius: 20px;
    box-shadow: 0 0px 20px #0000007b;
    padding: 40px;

}

.contact-form{
    width: 100%;
    justify-content: center;
}

.input-container{
    display: flex;
    flex-direction: column;
    margin-top: 20px;
}

.input{
    height: 40px;
    border-radius: 5px;
    padding-left: 20px;
    margin-top: 20px;
    border: #00000033 solid 1px;
    transition: all 0.2s linear;
}

.input:focus{
    border: none;
    box-shadow: 0 0 10px #4b362174;
    outline: 0;
}

.contact-button{
    display: block;
    border: none;
    background-color: #4b3621;
    color: white;
    width:100px;
    height: 40px;
    margin: 30px;
    justify-self: center;
    transition: background-color 0.2s linear;
}

.contact-button:hover{
    background-color: #1b140c;
}
</style>
<body class="w-full h-full">
    <header class="nav my-3 fade-up-nav">
        <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="nav-img">
        <ul class="flex justify-center align-items-center">
            <li class="nav-item nav-item-store text-xl active"><a href="#" class="nav-link active" aria-current="page">Home</a>
            </li>
            <li class="nav-item nav-item-store text-xl"><a href="{{ route('home') }}" class="nav-link">Shop</a></li>
            <li class="nav-item nav-item-store text-xl"><a href="#" class="nav-link">Contact</a></li>
        </ul>

    </header>
    <div class="hero-container">
        <div class="hero-text fade-up">
            <h3 class="montaga-regular text-7xl">The Art of the Khinjar</h3>
            <h3 class="montaga-regular text-2xl mt-5">Explore our collections of authentic Arabian Khinjars,<br>
            each crafted with timeless elegance and rich tradition</h3>
        </div>

    </div>
    <div class="about-us-container">
        <div class="about-us-imgs">
            <h3 class="montaga-regular text-4xl text-center fade-up">Featured Khinjars</h3>
            <div class="flex mt-10">
            <img src="{{ asset('images/khinjar-1.png') }}" alt="Logo" class="about-us-img fade-up hidden">
            <img src="{{ asset('images/khinjar-2.png') }}" alt="Logo" class="about-us-img fade-up hidden !delay-100">
            <img src="{{ asset('images/khinjar-3.png') }}" alt="Logo" class="about-us-img fade-up hidden !delay-200">
            </div>
        </div>
        <div class="about-text-container fade-up hidden">
            <h3 class="montaga-regular text-4xl">About Us</h3>
            <h3 class="montaga-regular text-lg mt-10">
                Al Khinjar Al Dhahbi is the UAE’s premier destination for authentic, handcrafted Emirati khinjars — the traditional Arabian dagger that symbolizes honor, heritage, and pride.

Rooted in culture and crafted with precision, each piece in our collection reflects a deep respect for tradition and a passion for timeless design. We work closely with skilled artisans to bring you khinjars that are not only rare collectibles, but powerful expressions of Emirati identity.

Whether you're a collector, a connoisseur, or someone seeking a meaningful gift, Al Khinjar Al Dhahbi invites you to explore a world where craftsmanship meets legacy.
            </h3>
        </div>


    </div>
    <div class="contact-container">
        <form class="contact-form" id="contactForm">
            <h3 class="montaga-regular text-4xl mb-15">Contact Us</h3>
            <div class="input-container">
                <label for="name" class="form-label text-xl">Name</label>
                <input type="text" class="input" name="name" id="name" placeholder="John Doe">
            </div>
            <div class="input-container">
                <label for="email" class="form-label text-xl">Email address</label>
                <input type="email" class="input" name="email" id="email" placeholder="name@example.com">
            </div>
            <div class="input-container">
                <label for="text-area" class="form-label text-xl">Message</label>
                <textarea name="message" id="message" rows="4" class="input block text-base" id="text-area"></textarea>
            </div>
            <button class="contact-button" type="submit">Submit</button>
        </form>
    </div>
</body>
<script>
document.getElementById('contactForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        message: formData.get('message'),
    };

    fetch('https://alkhinjaraldhahbiantiques.com/api/contact', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || 'Message sent successfully!');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong');
    });
});


</script>
</html>
