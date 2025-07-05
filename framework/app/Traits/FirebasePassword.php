<?php
namespace App\Traits;

use Kreait\Firebase\Factory;

trait FirebasePassword
{

    public function newpassword($email,$newpassword)
    {

        try{
            $firebase = (new Factory)
            ->withServiceAccount(storage_path('firebase/p2ptransfer-b7c05-firebase-adminsdk-bmmww-f253cb7e53.json')) // Ensure this file is correct
            ->createAuth();
        
            $email = $email;
            $newPassword = $newpassword;
            try {
                // Get user by email
                $user = $firebase->getUserByEmail($email);
                // Update the password directly
                $firebase->changeUserPassword($user->uid, $newPassword);
                //  response()->json([
                //     'message' => 'Password updated successfully',
                //     'user_id' => $user->uid,
                // ]);
            } catch (\Exception $e) {
                //response()->json(['error' => $e->getMessage()], 400);
            }
        }
        catch (\Exception $e) {
            //response()->json(['error' => $e->getMessage()], 400);
        }
      
    }

    public function deleteUser($email)
    {

        try {

        $firebase = (new Factory)
            ->withServiceAccount(storage_path('firebase/p2ptransfer-b7c05-firebase-adminsdk-bmmww-f253cb7e53.json'))
            ->createAuth();

        try {
            // Get user by email
            $user = $firebase->getUserByEmail($email);

            // Delete user by UID
            $firebase->deleteUser($user->uid);

            // return response()->json([
            //     'message' => 'User deleted successfully',
            //     'user_id' => $user->uid,
            // ]);
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 400);
        }

        }
        catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 400);
        }
    }
   

}