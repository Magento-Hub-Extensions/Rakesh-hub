<?php

namespace Ueg\Crm\Model;

class Appointmentoptions
{

	protected $authSession;

	protected $roles;

	protected $userInstance;


	public function __construct(
		\Magento\Backend\Model\Auth\Session $authSession,
		\Magento\Authorization\Model\RoleFactory $roleObject,
		\Magento\User\Model\UserFactory $userObject
	)
	{
		$this->roles = $roleObject;
		$this->authSession = $authSession;
		$this->userInstance = $userObject;
	}



	public function getMediaOptions()
	{
		$media = array(
			'Call',
			'Email',
			'In office'
		);

		return $media;
	}

	public function getAdminUserList()
	{
		$adminuserId = $this->authSession->getUser()->getUserId();
		$role_id = $this->roles->create()->load($adminuserId,'user_id')->getRoleId();
		// echo "roleid = $role_id"; exit;
		$list = array();
		// if($role_id == 1) {
		if($role_id == 6) {
			$users = $this->userInstance->create()->getCollection();
			if ( count( $users ) ) {
				foreach ( $users as $_user ) {
					$id          = $_user->getUserId();
					$list[ $id ] = $_user->getUsername();
				}
			}
		} else {
			$list[ $adminuserId ] = $this->authSession->getUser()->getUsername();
		}

		return $list;
	}

	public function getStatusOptions()
	{
		$status = array(
			'Set-up',
			'Ready',
			'Completed',
			'Closed'
		);

		return $status;
	}

	public function getOverdueStatusOptions()
	{
		$status = array(
			'Set-up',
			'Ready'
		);

		return $status;
	}

	public function getCrmOrderSecondaryStatusOptions()
	{
		$status = array(
			'Needs Research',
			'Research Completed',
			'Completed',
			'ASR Reps',
			'Suspicious',
			'Test Order'
		);

		return $status;
	}
}