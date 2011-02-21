<?php
class UserControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
	public function testCallWithoutActionShouldPullFromIndexAction()
	{
		$this->dispatch( '/user' );
		
		$this->assertResponseCode( 200 );
		$this->assertController( 'user' );
		$this->assertAction( 'index' );
	}

	
	public function testCallWithIndexActionShouldPullFromIndexAction()
	{
		$this->dispatch( '/user/index' );
		
		$this->assertResponseCode( 200 );
		$this->assertController( 'user' );
		$this->assertAction( 'index' );
	}
	
	
	public function testIndexActionShouldContainLoginForm()
	{
		$this->dispatch( '/user' );
		
		$this->assertQueryContentContains( 'h1', 'Login' );
		$this->assertQueryCount( 'form#login', 1 ); // id of form
	}
	
	
	public function testValidLoginShouldGoToProfilePage()
	{
		$this->request->setMethod( 'POST' )
					  ->setPost( array( 'username'	=> 'foobar',
					  					'password'	=> 'foobar' ) );
		$this->dispatch( '/user/login' );
		$this->assertRedirectTo( '/user/view' );
		
		$this->resetRequest()
			 ->resetResponse();
			 
		$this->request->setMethod( 'GET' )
					  ->setPost( array() );
		$this->dispatch( '/user/view' );
		$this->assertRoute( 'default' );
		$this->assertModule( 'default' );
		$this->assertController( 'user' );
		$this->assertAction( 'view' );
		$this->assertNotRedirect();
		$this->assertQuery( 'dl' );
		$this->assertQueryContentContains( 'h2', 'Utilisateur : foobar' );
	}
}
?>