<p align="center"> 
    <img src="https://user-images.githubusercontent.com/108950985/225649361-56dc0b8a-5a4f-4f2e-8dae-b10406212003.png" alt="Logo Factoría F5" width="120px">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <img src="https://user-images.githubusercontent.com/98114939/223130140-13c32ed0-9706-4454-a4f3-aa9fda31249b.png" alt="Logo Aidei" width="150px">
</p> 

<h3 align="center"> 
Leave Management Application  <img src="https://user-images.githubusercontent.com/108950985/225650398-d69cb649-716f-4fe5-acb2-95522d3246cb.png" alt="Emoticono calendario">
</h3

[AIDEI](http://www.aidei.es/) needs an app to be able to manage the holidays and absences of employees during their time with the company, in order to have more flexibility when managing this information.

***

## Table of contents 📖
  
* [Team Members](#team-members)
* [Screenshots](#screenshots)
* [Technologies](#stacks)
* [Installation](#how-to-install-this-project)
* [Tests](#tests)
* [Methodology](#methodology)
* [Next Steps](#next-steps)

***

## Team Members 👩‍💻

| Name | Role | <img src="https://github.com/Yelose/Yelose/blob/main/img/github.png" width="30px" height="30px"> |
| :--- | :---: | :---: |
| Lola Navarro | Scrum Master | https://github.com/ZLoln |
| Camila Ruiz | Scrum Master | https://github.com/camilaruiz17 |
| Ana Rueda | Product Owner | https://github.com/anaruedaguiu |
| Sierri Pérez | Developer | https://github.com/sierriconvoz |
| Veronika Komarova | Developer | https://github.com/VeronikaKoma |
| Paloma Ruiz | Developer| https://github.com/birdsinyourgarden |
| Adriana Aguilar | Developer | https://github.com/adrianaguilaruiz |

***

## Screenshots 📸

<h3 align="center">Sketchs views users</h3>
<p align="center">
    <img src="https://user-images.githubusercontent.com/98114939/223143156-eb815b23-1b53-4f30-bd04-8207963a57a6.png" alt="Sketch views users" width="200px">
</p>

<h3 align="center">Sketchs views admin</h3>
<p align="center"> 
    <img src="https://user-images.githubusercontent.com/98114939/223143373-2c43c6bb-e75b-46fc-aebe-5cb3bb444e99.png" alt="Sketch views admin" width="200px">
</p>

<h3 align="center">Mockups views users</h3>
<p align="center">
    <img src="https://user-images.githubusercontent.com/98114939/223143751-7f303569-d5ce-4256-9478-ad8ecce80530.png" alt="Mockup views users" width="200px">
</p>

<h3 align="center">Mockups views admin</h3>
<p align="center"> 
    <img src="https://user-images.githubusercontent.com/98114939/223144013-1b9e1a4b-c61b-47de-9b07-3b4498421569.png" alt="Mockup views admin" width="200px">
</p>

***

## Stacks 🛠

- HTML 5
- CSS 3
- BootStrap 5
- PHP 8.1
- Laravel Framework 10.0
- MySQL
- React
    
***

## How to install this project ⚙

The project is divided into two repositories: back and front end. You will need a text editor and clone both repositories. Follow the next steps:

**BACK**

1. Clone the project

Back-end
    
```bash
  git clone https://github.com/anaruedaguiu/vacacionesAIDEI.git
```

2. Go to the project directory
  
```bash
  cd vacacionesAIDEI
```

3. Start MySQL service and create a MySQL database named "aidei"

4. Create in the text editor an .env file by copying the contents of .env.example and modify the database name (laravel by aidei)

5. Install dependencies
  
```bash
  npm install
```
  
```bash
  composer install
```
  
6. Generate secret key 
  
```bash
  php artisan jwt:secret
```
  
This will update your .env file with something like JWT_SECRET=foobar. It is the key that will be used to sign your tokens.

7. Run the PHP server and keep this terminal open
  
```bash
  php artisan serve
```

8. Migrate database
  
```bash
  php artisan migrate:fresh --seed
```

**FRONT**

1. Clone the project

Front-end

```bash
  git clone https://github.com/camilaruiz17/front-end-react-final-proyect
```
  
2. Install the project's dependencies by running the following command
  
```bash
  npm install
```
  
3. Run the project and keep this terminal open
  
```bash
  npm run dev
```

***

## Tests ✅
  
To run the tests, execute the following command(s)
```bash
  php artisan test
```
<img width="500" alt="test_artisan_aidei_mvp2" src="https://user-images.githubusercontent.com/117440372/225344864-a18c2fab-06e1-440f-bade-ce775a69e808.png">

```bash
  vendor/bin/phpunit tests
```
<img width="500" alt="test_vendor_aidei_mvp2" src="https://user-images.githubusercontent.com/117440372/225345098-881dbe7f-65ff-4eeb-9a07-44e331260e24.png">

***

## Methodology 📚
  
- TDD
- Agile with Scrum
- Mob, pair and solo programming
  
***

## Next Steps 🔜

