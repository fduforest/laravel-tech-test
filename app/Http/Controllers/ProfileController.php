<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;

class ProfileController extends Controller {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(User $user, array $data ) {
		return Validator::make( $data, [
			'name'     => 'required|max:255',
			'email'    => 'required|email|max:255|unique:users,email,'.$user->id,
			'password' => 'required|confirmed|min:6',
		] );
	}

	/**
	 * Show the user's profile form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit()
	{
		if (Auth::user())
		{
			$user = Auth::user();

			return view( 'admin.profile')->withUser( $user );
		}
	}

	/**
	 * Handle a profile update request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateProfile( Request $request ) {

		if ( Auth::user() ) {
			$user = Auth::user();

			$validator = $this->validator($user, $request->all() );

			if ( $validator->fails() ) {
				$this->throwValidationException(
					$request, $validator
				);
			}

			$this->update($user, $request->all() );

			return redirect( 'user/'.$user->id )->withMessage( 'Profile updated successfully' );
		} else {
			return redirect( '/' )->withErrors( 'you have not sufficient permissions' );
		}
	}

	/**
	 * Update the user's profile.
	 *
	 * @return view
	 */
	public function update(User $user, array $data ) {

			$user           = Auth::user();
			$user->name     = $data['name'];
			$user->email    = $data['email'];
			$user->password = bcrypt( $data['password'] );

			$user->save();
	}


}