<?php
namespace WdgUser\Service;

use Zend\Authentication\AuthenticationService,
    Zend\Form\Form,
    Zend\Crypt\Password\Bcrypt,
    WdgBase\Service\ServiceAbstract,
    WdgUser\Entity\User as UserEntity;


class User extends ServiceAbstract
{
    /**
     * @var \WdgUser\Repository\User
     */
    protected $userRepos;

    /**
     * @var Form
     */
    protected $editForm;

    /**
     * @var Form
     */
    protected $addForm;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    
    /**
     * @param int $id
     * @return UserEntity
     */
    public function get($id)
    {
        return $this->getUserRepository()->find($id);
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAll()
    {
        return $this->getUserRepository()->findAll();
    }
    
    public function getOneByUserName($username)
    {
        return $this->getUserRepository()->findOneBy(array("username" => $username));
    }
    
    public function getUsersSelectArray()
    {
        $users = $this->getUserRepository()->findAll();
        
        foreach($users as $user)
        {
            $array[$user->getId()] = $user->getDisplayName();
        }
        
        return $array;
    }

    /**
     * createFromForm
     *
     * @param array $data
     * @return \WdgUser\Entity\User
     * @throws Exception\InvalidArgumentException
     */
    public function add(array $post)
    {
        $form   = $this->getAddForm();
        $em     = $this->getEntityManager();
        $bcrypt = $this->getNewEncryptorInstance();
        
        $form->setData($post);
        
        if(!$form->isValid())throw new \WdgUser\Exception\Service\User\FormException("Form values are invalid");

        $data   = $form->getInputFilter()->getValues();
        $User   = new UserEntity();
        
        $User->setUsername($data["username"])
            ->setEmail($data["email"])
            ->setDisplayName($data["display_name"])
            ->setState(1)
            ->setPassword($bcrypt->create($User->getPassword()));
        
        $em->persist($User);        
        $em->flush();
        
        return $User;
    }

    /**
     * createFromForm
     *
     * @param array $data
     * @return \WdgUser\Entity\User
     * @throws Exception\InvalidArgumentException
     */
    public function edit(array $post)
    {
        $form   = $this->getEditForm();
        $em     = $this->getEntityManager();
        
        $form->setData($post);
        
        if(!$form->isValid())throw new \WdgUser\Exception\Service\User\FormException("Form values are invalid:");

        $data   = $form->getInputFilter()->getValues();
        $User   = $this->get($data["id"]);
        
        $User->setUsername($data["username"])
            ->setEmail($data["email"])
            ->setDisplayName($data["display_name"])
            ->setState($data["state"]);
        
        $em->persist($User);        
        $em->flush();
        
        return $User;
    }
    
    public function remove($id)
    {
        $User   = $this->get($id);
        
        if(!$User)throw new \Exception("No user with that id.");
        
        $em     = $this->getEntityManager();
        
        $em->remove($User);
        $em->flush();
        
        return $this;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getUserRepository()
    {
        if (null === $this->userRepos)
        {
            $this->userRepos = $this->getServiceManager()->get('wdguser_user_repos');
        }
        
        return $this->userRepos;
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $userRepos
     * @return User
     */
    public function setUserRepository(\Doctrine\ORM\EntityRepository $userRepos)
    {
        $this->userRepos = $userRepos;
        
        return $this;
    }

    /**
     * @return Form
     */
    public function getEditForm($id = null)
    {
        if (null === $this->editForm)
        {
            $this->editForm = $this->getServiceManager()->get('FormElementManager')->get('wdguser_edit_form');
        }
        
        $form = $this->editForm;
        
        if($id && $user = $this->get($id))
        {
            $form->populateValues($user->toArray());
        }
        
        return $this->editForm;
    }

    /**
     * @return Form
     */
    public function getAddForm()
    {
        if (null === $this->addForm)
        {
            $this->addForm = $this->getServiceManager()->get('FormElementManager')->get('wdguser_add_form');
        }
        
        return $this->addForm;
    }

    /**
     * @param Form $editForm
     * @return User
     */
    public function setEditForm(Form $editForm)
    {
        $this->editForm = $editForm;
        
        return $this;
    }
    
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        if($this->entityManager === null)
        {
            $this->entityManager = $this->getServiceManager()->get("doctrine.entity_manager.orm_default");
        }
        
        return $this->entityManager;
    }
    
    /**
     * @return \Zend\Crypt\Password\Bcrypt
     */
    protected function getNewEncryptorInstance()
    {
        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        
        $bcrypt->setCost($this->getPasswordCost());
        
        return $bcrypt;
    }
    
    protected function getPasswordCost()
    {
        return 14;
    }
}
