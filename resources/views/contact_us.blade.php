<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>KON - Privacy Policy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor"
      crossorigin="anonymous"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
      crossorigin="anonymous"
    ></script>
  </head>
  <style>
    body {
      font-family: "Nunito", sans-serif;
    }
    p,
    pre,
    article,
    a,
    ol,
    ul,
    li {
      font-size: 18px;
      font-weight: 600;
    }
    a {
      word-break: break-all;
    }
    h1 {
      font-weight: 800;
      text-align: center;
      padding: 20px 0;
      font-size: 52px;
    }
    h2 {
      font-weight: 700;
      padding-bottom: 20px;
    }
    @media only screen and (max-width: 700px) {
      h1 {
        font-size: 28px;
      }
    }
  </style>
  <body>
    <nav class="navbar bg-dark">
      <div class="container">
        <a
          class="navbar-brand text-center d-block mx-auto bg-light rounded-3 p-2 mb-2 mt-2 text-dark"
          href="/"
        >
          <h2 class="text-center m-0 p-0">KON App</h2>
        </a>
      </div>
    </nav>
    <main class="container">
      <!--Section: Contact v.2-->
      <section class="mb-4">

      <!--Section heading-->
      <h1 class="h1-responsive font-weight-bold text-center my-4">Contact us</h1>
      <!--Section description-->
      <p class="text-center w-responsive mx-auto mb-5 col-md-6">¿Tiene alguna pregunta? No dude en ponerse en contacto con nosotros directamente. Nuestro equipo se pondrá en contacto con usted en
          cuestión de horas para ayudarle.</p>

      <div class="row">

          <!--Grid column-->
          <div class="col-md-6 offset-md-3 mb-md-0 mb-5">
              <form id="contact-form" name="contact-form" action="{{route('contact_us')}}" method="POST">
                  @csrf
                  <!--Grid row-->
                  <div class="row mb-4">

                      <!--Grid column-->
                      <div class="col-md-6">
                          <div class="md-form mb-0">
                              <label for="name" class="">Su nombre</label>
                              <input required type="text" id="name" name="name" class="form-control">
                          </div>
                      </div>
                      <!--Grid column-->

                      <!--Grid column-->
                      <div class="col-md-6">
                          <div class="md-form mb-0">
                              <label for="email" class="">Su correo electrónico</label>
                              <input required type="email" id="email" name="email" class="form-control">
                          </div>
                      </div>
                      <!--Grid column-->

                  </div>
                  <!--Grid row-->

                  <!--Grid row-->
                  <div class="row mb-4">
                      <div class="col-md-12">
                          <div class="md-form mb-0">
                              <label for="subject" class="">Asunto</label>
                              <input required type="text" id="subject" name="subject" class="form-control">
                          </div>
                      </div>
                  </div>
                  <!--Grid row-->

                  <!--Grid row-->
                  <div class="row mb-4">

                      <!--Grid column-->
                      <div class="col-md-12">

                          <div class="md-form">
                              <label for="message">Su mensaje</label>
                              <textarea type="text" id="message" name="message" rows="2" class="form-control md-textarea"></textarea>
                          </div>

                      </div>
                  </div>
                  <!--Grid row-->

                  <div class="text-center text-md-left">
                      <button class="btn btn-primary" type="submit">Send</button>
                  </div>
                  <div class="status"></div>
              </form>

          </div>
          <!--Grid column-->
          <!--Grid column-->

      </div>

      </section>
      <!--Section: Contact v.2-->
    </main>
    <footer class="bg-dark p-0 m-0">
      <div class="container">
        <p class="text-center p-3 m-0 mt-2 text-light">
          Todos los derechos reservados a KON App
        </p>
      </div>
    </footer>
  </body>
</html>
