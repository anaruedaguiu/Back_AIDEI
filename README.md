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
| Ana Rueda | Product Owner | https://github.com/anaruedaguiu |
| Camila Ruiz | Developer| https://github.com/camilaruiz17 |
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
    <img width="200" alt="Mockup views users" src="https://user-images.githubusercontent.com/98114939/225834704-10cfd6d2-c02e-4fee-9f66-004144f6acbd.png">
</p>


<h3 align="center">Mockups views admin</h3>
<p align="center"> 
    <img width="200" alt="Mockup views admin 1" src="https://user-images.githubusercontent.com/98114939/225835094-7f54dd92-b8d4-4585-813a-d14b6bb2d38c.png">
    <img width="200" alt="Mockup views admin 2" src="https://user-images.githubusercontent.com/98114939/225835110-e6e9e3d9-9603-45d2-9b22-ffd76c1d062b.png">
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

**BACK**

```bash
  php artisan test
```
<img width="628" alt="test_artisan_aidei_presentación" src="https://user-images.githubusercontent.com/98114939/228551487-e6ced1fd-2198-4c74-8063-2fa9ff23644b.png">

```bash

  vendor/bin/phpunit
```
<img width="595" alt="test_vendor_aidei_presentación" src="https://user-images.githubusercontent.com/98114939/228551607-af91fdc5-eee4-4e94-b320-432e5f64c9e1.png">

**FRONT**

```bash

  npm run test
```
<img width="444" alt="test_npm_aidei_presentación" src="https://user-images.githubusercontent.com/98114939/228554458-35c1ee09-8abd-477d-812e-aacd07c93875.png">



***

## Methodology 📚
  
- Testing
- Agile with Scrum & Kanban
- Mob, pair and solo programming
  
***

## Next Steps 👣

- Finish connecting Back and Front.
- Ensure that documents attached in absences are not only uploaded to Cloudinary, but also saved in the database.
- Modify the employee's update so that they can edit certain fields of their personal information. For example: change the password they use to log in to the application or their profile picture.
- Create a table for the status of absence requests and another for holidays, so that the scalability of the code is maintained and the status of the request can be incorporated into the application: 
PENDING - IN PROCESS - PROCESSED
- Adding more functionalities to the calendar.
- Correct the user endingDate field so that it works exactly as we want it to.

