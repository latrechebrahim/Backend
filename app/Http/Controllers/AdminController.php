<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Appointments;
use App\Models\Hospital;
use Exception;

class AdminController extends Controller
{
    function RegisterDoctors(Request $request)
    {
        try {
            $Doctor = new Doctor();
            $Doctor->firstname = $request->firstname;
            $Doctor->lastname = $request->lastname;
            $Doctor->specialty = $request->specialty;
            $Doctor->phonenumber = $request->phonenumber;
            $Doctor->email = $request->email;
            $Doctor->date_birth = $request->date_birth;
            $Doctor->path = $request->path;
            $Doctor->admin = $request->admin;
            $Doctor->isAvailable = $request->isAvailable;
            $Doctor->password = Hash::make($request->password);
            $Doctor->confirmpassword = Hash::make($request->confirmpassword);
            $Doctor->save();
            $response = ['status' => 200, 'message' => 'Register Successfully!'];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => 'Error while registering Doctor'];
            return response()->json($response);
        }
    }

    function RegisterHospital_info(Request $request)
    {
        try {
            $info = new Hospital();
            $info->Name = $request->Name;
            $info->website = $request->website;
            $info->email = $request->email;
            $info->phonenumber = $request->phonenumber;
            $info->save();
            $response = ['status' => 200, 'message' => 'Register Hospital_info Successfully!'];
            return response()->json($response);
        } catch (Exception $e) {
            echo ($e);
            $response = ['status' => 500, 'message' => 'Error while registering  Hospital_info'];
            return response()->json($response);
        }
    }
    public function updateHospitalInfo(Request $request)
    {
        try {
            // Retrieve the hospital record by its ID
            $id = $request->input('id');
            $info = Hospital::findOrFail($id);

            // Validate the request data
            $validatedData = $request->validate([
                'Name' => 'required',
                'website' => 'required',
                'email' => 'required|email',
                'phonenumber' => 'required',
            ]);

            // Update the specific fields with the validated data
            $info->fill($validatedData);

            // Save the changes to the database
            $info->save();

            // Return a success response
            $response = ['status' => 200, 'message' => 'Hospital info updated successfully'];
            return response()->json($response);
        } catch (Exception $e) {
            // Return an error response if an exception occurs
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }


    public function showHospital_info($id)
    {
        try {
            $Info = Hospital::find($id);
            $response = ['status' => 200, 'users' => $Info,];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }
    public function showAllDoctors()
    {
        try {
            $users = Doctor::where('admin', 0)->get();
            $response = ['status' => 200, 'users' => $users,];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }

    public function showAllAppointments()
    {
        try {
            $appointments = Appointments::all();
            $appointmentsData = [];

            foreach ($appointments as $appointment) {
                $patientId = $appointment->PatientId;
                $doctorId = $appointment->DoctorId;


                $userData = $this->showappointment($patientId, $doctorId);

                $appointmentsData[] = [
                    'appointment' => $appointment,
                    'userData' => $userData,
                ];
            }

            $response = ['status' => 200, 'appointmentsData' => $appointmentsData];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }

    public function count()
    {
        try {
            $countDoctor = Doctor::where('admin', 0)->count();
            $countUsers = User::count();
            $countAppointment = Appointments::count();
            $response = ['status' => 200, 'countDoctor' => $countDoctor, 'countUsers' => $countUsers, 'countAppointment' => $countAppointment];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }

    public function deleteDoctor(Request $request)
    {
        try {
            $doctor = Doctor::find($request->id);
            if (!$doctor) {
                $response = ['status' => 404, 'message' => 'Doctor not found'];
                return response()->json($response);
            }

            $appointments = Appointments::where('DoctorId', $doctor->id)->get();
            foreach ($appointments as $appointment) {
                $appointment->delete();
            }
            $doctor->delete();

            $response = ['status' => 200, 'message' => 'Doctor and associated appointments deleted successfully'];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => 'Error while deleting doctor'];
            return response()->json($response);
        }
    }


    /////////////////////////////////////////Patients//////////////////////////////
    public function deletePatient(Request $request)
    {
        try {
            $Patient = User::find($request->id);
            if (!$Patient) {
                $response = ['status' => 404, 'message' => 'Patient not found'];
                return response()->json($response);
            }

            $appointments = Appointments::where('PatientId', $Patient->id)->get();
            foreach ($appointments as $appointment) {
                $appointment->delete();
            }
            $Patient->delete();

            $response = ['status' => 200, 'message' => 'Patient and associated appointments deleted successfully'];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => 'Error while deleting Patient'];
            return response()->json($response);
        }
    }

    public function showAllUsers()
    {
        try {
            $users = User::all();
            $response = ['status' => 200, 'users' => $users];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }

    public function showappointment($PatientId, $DoctorId)
    {
        try {
            $patient = User::find($PatientId);
            $doctor = Doctor::find($DoctorId);
            $userData = ['Patient' => $patient, 'Doctor' => $doctor];

            return response()->json(['status' => 200, 'userData' => $userData]);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }
}
