#  CareNova – Healthcare Management System  
Full-stack Graduation Project (Laravel + Angular)
CareNova is an integrated healthcare system designed to manage patients, doctors, appointments, analytics, payments, and provide intelligent symptom analysis using artificial intelligence.
The project is part of an ITI (Information Technology Institute) grant.
---

##  **Features**

###  Authentication & User Roles
- Login and registration of new users.
- Logout and session management (JWT/LocalStorage).
- System Roles:
- **Admin**
- **Doctor**
- **Patient**
- Redirecting users according to their role.

---

##  **Admin Dashboard**
- Advanced dashboard for displaying statistics:
- Number of patients
- Number of doctors
- Number of appointments (Pending / Confirmed / Cancelled / Completed)
- Total payments
- Full control over elements:
- CRUD for patients
- CRUD for doctors
- CRUD for appointments
- CRUD for specialties
- CRUD for payments
---

##  **Doctors Module**
- Viewing doctor profiles.
- Managing appointments for each doctor.
- Confirming / Cancelling / Completing appointments.
---

##  **Patients Module**
- View profile.
- Upload and manage profile picture.
- Book appointments.
- Track appointment status (Pending → Confirmed → Completed).
- View previous medical analyses.

---

##  **AI Symptoms Analysis**
- Smart page for symptom analysis using OpenAI.
- Patient submits:
- Description of condition
- Or a PDF file/image
- System returns:
- Possible diagnoses
- Confidence percentage
- Risk level
- Recommendations to see a specialist
- The result is displayed in a formatted interface.

---

##  **Admin Logs System**
- Logs all system events:
- Create/Update/Delete logs
- User who performed the action
- Model type
- Event details
- Admin interface for viewing logs with:
- Filtering
- Pagination
- Advanced search

---
##  **Tech Stack**

### **Frontend (Angular)**
- Angular 
- TailwindCSS
- Full Responsive UI
- JWT Authentication
- Angular Routing & Guards
- Reusable Components (Navbar, Profile, Appointments, AI Checker)

### **Backend (Laravel)**
- Laravel 12
- Laravel Breeze (Auth)
- Sanctum (API Auth)
- Laravel Eloquent ORM
- Laravel Storage (For profile images)
- API Resources
- Middleware (Auth – Admin – Doctor)

### **Database**
- MySQL

### **AI Integration**
- OpenAI API  
Custom medical analysis form.
---
##  **Project Structure**
### **Backend**
backend/
├── app/
│ ├── Models/
│ ├── Http/
│ │ ├── Controllers/
│ │ ├── Middleware/
│ │ └── Resources/
│ ├── Services/
├── routes/
│ ├── api.php
│ └── web.php
├── database/
└── public/


### **Frontend**
frontend/
├── src/app/
│ ├── auth/
│ ├── navbar/
│ ├── patient-profile/
│ ├── doctor-profile/
│ ├── symptoms-checker/
│ ├── services/
│ └── appointments/
├── assets/
└── environments/


---

##  **Installation & Run (Backend)**

###  Clone project
1️⃣```bash
git clone https://github.com/AmiraMohamed56/ITI_Graduation_Project.git
cd ITI_Graduation_Project/backend
###  Installation & Run (backend)
2️⃣ Install dependencies
composer install
3️⃣ Create .env file
cp .env.example .env
4️⃣ Generate key
php artisan key:generate
5️⃣ Run migrations
php artisan migrate --seed
6️⃣ Serve
php artisan serve
### Installation & Run (Frontend)
1️⃣ Navigate to frontend
cd ../frontend
2️⃣ Install packages
npm install
3️⃣ Run Angular dev server
ng serve --open

### API Endpoints Overview
(In short – a complete list is available within the project)
### Auth
POST /api/login
POST /api/register
POST /api/logout

### Patients
GET /api/patients
POST /api/patients
PATCH /api/patients/{id}
DELETE /api/patients/{id}

### AI
POST /api/symptoms/analyze

### Appointments
GET /api/appointments
POST /api/appointments
PATCH /api/appointments/{id}

### Team Members
- Ahmed Ibrahim
- ----- https://github.com/ahmed29112000
- Amira Mohamed
- ----- https://github.com/AmiraMohamed56
- Alaa Amr
- ----- https://github.com/AlaaAmr153
- Asma Othman
- ----- https://github.com/syntax-weaver
- hagar elhalfawy
- ----- https://github.com/HagarElhalfawy

