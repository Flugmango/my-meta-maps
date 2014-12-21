<?php

class UserApiController extends BaseApiController {
	
	public function postLogin($method) {
		
	}
	
	public function postLogout() {
		
	}
	
	public function postRegister() {
		$user = new User();

		$data = Input::only('name', 'email', 'password', 'password_confirmation');
		
		$validator = Validator::make($data,
			array(
				'name' => 'required|min:3|max:60|unique:'.$user->getTable(),
				'email' => 'required|email|unique:'.$user->getTable(),
				'password' => 'required|confirmed|min:4|max:255',
				'password_confirmation' => 'required'
			)
		);
		
		if ($validator->fails()) {
			return $this->getConflictResponse($validator->messages());
		}
		
		$user->name = $data['name'];
		$user->email = $data['email'];
		$user->password = $data['password']; // TODO: Hashing
		if ($user->save()) {
			return $this->getJsonResponse();
		}
		else{
			return $this->getErrorResponse();
		}
	}
	
	public function postChange($what) {
		
	}
	
	public function postCheck($what) {
		$rules = array();

		switch($what) {
			case 'email':
			case 'name':
				$user = new User();
				$rules[$what] = 'unique:'.$user->getTable();
				break;
			default: 
				return $this->getConflictResponse();
		}

		$validator = Validator::make(Input::only($what), $rules);
		if ($validator->fails()) {
			return $this->getConflictResponse();
		}
		else {
			return $this->getJsonResponse();
		}
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function postRemindRequest() {
		switch ($response = Password::remind(Input::only('email')))
		{
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));

			case Password::REMINDER_SENT:
				return Redirect::back()->with('status', Lang::get($response));
		}
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function postRemindReset() {
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function($user, $password)
		{
			$user->password = Hash::make($password);

			$user->save();
		});

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));

			case Password::PASSWORD_RESET:
				return Redirect::to('/');
		}
	}
	
}
