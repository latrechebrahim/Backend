<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointments;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;

use Exception;


class UserController extends Controller
{
    ///////////////////////Patients////////////////////////////////
    function Register(Request $request)
    {
        try {
            $user = new User();
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->phonenumber = $request->phonenumber;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->confirmpassword = Hash::make($request->confirmpassword);
            $user->save();

            $response = ['status' => 200, 'message' => 'Register Successfully! welcome to our platform'];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => 'Error while registering user'];
            return response()->json($response);
        }
    }

    public function updateInfo(Request $request, $status)
    {

        $id = $request->input('id');

        try {
            if ($status == 'Patient') {

                $Patient = User::find($id);
                if (!$Patient) {
                    throw new Exception('Patient not found');
                }
                $fieldsToUpdatePatient = $request->only(['firstname', 'lastname', 'email', 'phonenumber', 'date_birth', 'path']);

                $Patient->update($fieldsToUpdatePatient);

                $response = ['status' => 200, 'message' => 'Patient updated successfully', 'Patient' => $Patient];
            } elseif ($status == 'Doctor') {

                $Doctor = Doctor::find($id);
                if (!$Doctor) {
                    throw new Exception('Doctor not found: ');
                }

                $fieldsToUpdateDoctor = $request->only([
                    'firstname',
                    'lastname',
                    'specialty',
                    'email',
                    'phonenumber',
                    'date_birth',
                    'path',
                    'password',
                    'confirmpassword',
                ]);
                if ($request->has('password') && $request->has('confirmpassword')) {
                    $password = Hash::make($request->input('password'));
                    $confirmpassword = Hash::make($request->input('confirmpassword'));
                    $fieldsToUpdateDoctor['password'] = $password;
                    $fieldsToUpdateDoctor['confirmpassword'] = $confirmpassword;
                }

                $Doctor->update($fieldsToUpdateDoctor);

                $response = ['status' => 200, 'message' => 'Doctor Info updated successfully'];
            }


            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }

    public function showInfo($patientId)
    {
        $user = User::find($patientId);
        if (!$user) {
            return response()->json(['status' => 404, 'message' => 'User not found'], 404);
        }
        return response()->json(['status' => 200, 'user' => $user], 200);
    }


    public function CreateAppointment(Request $request, $doctorId, $patientId)
    {
        try {
            $Appointment = new Appointments();
            $Appointment->DoctorId = $doctorId;
            $Appointment->PatientId = $patientId;
            $Appointment->content = $request->content;
            $Appointment->confirmed = $request->confirmed;
            $Appointment->date = $request->date;
            $Appointment->save();
            $response = ['status' => 200, 'message' => 'create Appointment Successfully! welcome '];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => 'Error while create Appointment '];
            return response()->json($response);
        }
    }

    public function showDoctors()
    {
        try {
            $users = Doctor::where('admin', 0)
                ->where('isAvailable', 1)
                ->get();
            $response = ['status' => 200, 'users' => $users,];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }

    public function getSpecialtyAndFirstname()
    {
        try {
            $users = Doctor::where('admin', 0)
                ->where('isAvailable', 1)
                ->select(DB::raw("CONCAT(id,'_',specialty, '_', firstname) as id_specialty_firstname"))
                ->get();

            $specialtyFirstnames = $users->pluck('id_specialty_firstname')->toArray();
            $response = ['status' => 200, 'specialtyFirstnames' => $specialtyFirstnames];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }




    ///////////////////////Doctors////////////////////////////////


    public function showInfoDoctor($DoctorId)
    {
        $Doctor = Doctor::find($DoctorId);
        if (!$Doctor) {
            return response()->json(['status' => 404, 'message' => 'User not found'], 404);
        }
        return response()->json(['status' => 200, 'user' => $Doctor], 200);
    }

    public function updateAppointments(Request $request)
    {

        $Id = $request->input('id');

        try {
            $Appointment = Appointments::find($Id);
            if (!$Appointment) {
                throw new Exception('Appointment not found');
            }

            $fieldsToUpdate = $request->only(['confirmed', 'date']);

            $Appointment->update($fieldsToUpdate);

            $response = ['status' => 200, 'message' => 'Appointment updated successfully'];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }

    public function deleteAppointment(Request $request)
    {
        $Id = $request->input('id');

        try {
            $Appointment = Appointments::find($Id);
            if (!$Appointment) {
                throw new Exception('Appointment not found');
            }
            $Appointment->delete();

            $response = ['status' => 200, 'message' => 'Appointments deleted successfully'];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }

    public function countAppointmentsDoctor($DoctorId)
    {
        try {
            $countAppointments = Appointments::where('DoctorId', $DoctorId)
                ->count();
            $countAppointmentsNotconfirmed = Appointments::where('DoctorId', $DoctorId)
                ->where('confirmed', 0)
                ->count();

            $response = ['status' => 200, 'countAppointments' => $countAppointments, 'countAppointmentsNotconfirmed' => $countAppointmentsNotconfirmed];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e->getMessage()];
            return response()->json($response);
        }
    }





    ///////////////////////////////////////////////////////////////////
    public function showAppointments($Id, $status)
    {
        try {
            $appointmentsData = [];

            if ($status === 'Doctor') {
                $appointments = Appointments::where('DoctorId', $Id)->get();
            } elseif ($status === 'Patient') {
                $appointments = Appointments::where('PatientId', $Id)->get();
            } else {
                throw new Exception('Invalid status parameter.');
            }

            // Iterate through filtered appointments
            foreach ($appointments as $appointment) {
                // Get user data based on the $status parameter
                if ($status === 'Doctor') {
                    $userData = $this->showappointment($appointment->PatientId, $Id);
                } elseif ($status === 'Patient') {
                    $userData = $this->showappointment($Id, $appointment->DoctorId);
                }

                // Construct appointmentsData array
                $appointmentsData[] = [
                    'appointment' => $appointment,
                    'userData' => $userData,
                ];
            }

            // Return response with appointmentsData
            $response = ['status' => 200, 'appointmentsData' => $appointmentsData];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 300, 'message' => $e->getMessage()];
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

    public function ResetPass(Request $request, $Id, $status)
    {
        $Newpass = $request->input('Newpassword');
        $Newconpass = $request->input('Newconfirmpassword');
        $Oldpass = $request->input('password');

        if ($status == 'Doctor') {
            $User = Doctor::find($Id);
        } elseif ($status === 'Patient') {
            $User = User::find($Id);
        }



        if (!$User) {
            $response = ['status' => 401, 'message' => 'No account found with this User ID. Please try again.'];
            return response()->json($response);
        }
        if (empty($Oldpass) || empty($Newpass) || empty($Newconpass)) {
            $response = ['status' => 400, 'message' => 'Old password, new password, and confirm password are required.'];
            return response()->json($response);
        }

        // Check if the old password matches the stored password
        if (!Hash::check($Oldpass, $User->password)) {
            $response = ['status' => 402, 'message' => 'Old password is incorrect. Please try again.'];
            return response()->json($response);
        }

        // Check if the new password and confirm password match
        if ($Newpass !== $Newconpass) {
            $response = ['status' => 403, 'message' => 'confirm password not match.'];
            return response()->json($response);
        }

        // Update the password
        $User->password = Hash::make($Newpass);
        $User->save();

        $response = ['status' => 200, 'message' => 'Password reset successfully.'];
        return response()->json($response);
    }
}
