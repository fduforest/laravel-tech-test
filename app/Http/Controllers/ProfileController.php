<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ProfileController extends Controller {

	public $user;

	/**
	 * ProfileController constructor.
	 *
	 */
	public function __construct() {
		$this->user = Auth::user();
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator( array $data ) {
		return Validator::make( $data, [
			'name'     => 'required|max:255',
			'email'    => 'required|email|max:255|unique:users,email,' . $this->user->id,
			'password' => 'required|confirmed|min:6',
		] );
	}

	/**
	 * Show the user's profile form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show() {

		if ( $this->user ) {

			return view( 'admin.profile' )->withUser( $this->user );
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

		if ( $this->user ) {

			$validator = $this->validator($request->all() );

			if ( $validator->fails() ) {
				$this->throwValidationException(
					$request, $validator
				);
			}

			$this->update( $request->all() );

			return redirect( 'user/' . $this->user->id )->withMessage( 'Profile updated successfully' );
		} else {
			return redirect( '/' )->withErrors( 'you have not sufficient permissions' );
		}
	}

	/**
	 * Update the user's profile.
	 *
	 * @return view
	 */
	public function update(array $data ) {

		$this->user           = Auth::user();
		$this->user->name     = $data['name'];
		$this->user->email    = $data['email'];
		$this->user->password = bcrypt( $data['password'] );

		$this->user->save();
	}

	/**
	 * Handle a profile update request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLastScore( Request $request ) {

		$score = $request->all();

		return redirect( 'user/' . $this->user->id )->withScore( $score );
	}

	/**
	 * Handle a profile update request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateScore( Request $request ) {

		$data = $request->all();

		$this->user->score = $data['score'];

		$this->user->save();

	}


}