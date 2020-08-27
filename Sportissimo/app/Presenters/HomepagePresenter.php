<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Model\TableManager;
use Nette\Application\UI\Form;
use Tracy\Debugger;


/**
 * Presenter for main page - In this case Brands.
 * Class HomepagePresenter
 * @package App\Presenters
 * @author Petr Zeman
 * @version v1.0
 */
final class HomepagePresenter extends Nette\Application\UI\Presenter
{

    /** Would get inputted from user normally, but for this page I used predefined */
    private $username = 'admin';

    /** @persistent */
    public $backlink = '';

    /** @var TableManager\ */
    private $tableManager;

    public function __construct(TableManager $tableManager)
    {
        $this->tableManager = $tableManager;
    }

    /**
     * @param int $page - Current page in table pagination
     * @param int $tableSize - Nr. of rows in table
     * @param string $order - Order of sorting
     */
    public function renderDefault(int $page=1,int $tableSize = 10,string $order = 'ASC'):void {

        $this->backlink=$this->storeRequest();
        $brands = $this->tableManager->getTable($order);

        $lastPage = 0;
        $this->template->brand = $brands->page($page,$tableSize,$lastPage);

        /** Prevents nonsensical pagination if table size was changed with pagination too deep. */
        if($page>$lastPage){
            $page = $lastPage;
            $this->template->brand = $brands->page($page,$tableSize,$lastPage);
        }


        /**
         * Cleans pagination on edges.
         */
        if($lastPage>7) {
            $paginationOffsetBottom = $page - 3;
            $paginationOffsetTop = ($page + 2) - $lastPage;
            if (($paginationOffsetBottom) < 0) {
                $paginationOffset = -$paginationOffsetBottom;
            } else if (($paginationOffsetTop) > 0) {
                $paginationOffset= -$paginationOffsetTop;
            } else {
                $paginationOffset = 0;
            }
        }else {
            $paginationOffset = 0;
        }


        $this->template->tableSize = $tableSize;
        $this->template->order = $order;
        $this->template->paginationOffset = $paginationOffset;
        $this->template->page = $page;
        $this->template->lastPage = $lastPage;
        $this->template->username = $this->username;


    }

    /**
     * Utilised for deletion of brand - Could be made in form instead.
     * @param int $id - Id of brand to be deleted
     */
    public function handleDelete(int $id): void
    {

            $this->tableManager->deleteRow($id);
            $this->restoreRequest($this->backlink);
            $this->redirect('default');
    }

    /**
     * Creates a new form for creation of new brand - In bigger application would be moved to FormFactory
     * @return Form - prepared form for creation of new brand
     */
    public function createComponentNewBrandForm(){
        $form = new Form();
        $form->addText('brandName','Brand Name:')
            ->setRequired();
        $form->addSubmit('submit')
            ->onClick[] = [$this, 'createNewBrand'];
        $form->addProtection();
        return $form;
    }

    /**
     * onClick handler of newBrand form - In bigger application would be moved to FormFactory
     * @param $form - Filled in form
     */
    public function createNewBrand($form){
        $values=$form->getForm()->getValues();
        if(! $this->tableManager->addRow($values)){
            $form->getForm()->addError('Duplicate Entry!');
        }

    }

    /**
     * Creates a new form for updating the name of brand - In bigger application would be moved to FormFactory
     * @return Form - prepared form for updating the name of brand
     */
    public function createComponentUpdateBrandForm(){

        $form = new Form();
        $form->addText('brandName','Brand Name:')
            ->setRequired();
        $form->addHidden('id',0);
        $form->addSubmit('submit')
            ->onClick[] = [$this, 'updateBrand'];
        $form->addProtection();
        return $form;
    }

    /**
     * onClick handler of updateBrand form - In bigger application would be moved to FormFactory
     * @param $form - Filled in form
     */
    public function UpdateBrand($form){
        $values=$form->getForm()->getValues();
        if(! $this->tableManager->updateRow($values)){
            $form->getForm()->addError('Duplicate Entry!');
        }

    }





}
