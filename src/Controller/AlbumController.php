<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.09.18
 * Time: 9:59
 */

namespace Gallery\Controller;

use Doctrine\ORM\EntityManager;
use Repository\PhotoRepository;
use Gallery\Entity\Album;
use Gallery\Entity\Author;
use Gallery\Entity\Photo;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Slim\Views\PhpRenderer;
use Gallery\Form\AlbumForm;
use Gallery\Form\PhotoForm;
use Zend\View\Model\ViewModel;


class AlbumController
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Post manager.
     * @var Gallery\Service\AlbumManager
     */
    private $albumManager;

    /**
     * @var Slim\Views\PhpRenderer
     */
    private $renderer;

    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($entityManager, $albumManager, $renderer)
    {
        $this->entityManager = $entityManager;
        $this->albumManager = $albumManager;
        $this->renderer=$renderer;
    }

    public function indexAction($request, $response, $args)
    {
        //экшн демонстрации списка альбомов
        //$albums = $this->entityManager->getRepository(Album::class)->findBy(['lastModifiedAt'=>'DateTime']);
        $albums = $this->entityManager->getRepository(Album::class)->findBy([],['id'=>'DESC']);
        return $this->renderer->render($response, 'albums.phtml', ['albums' => $albums]);
    }

    public function createAction($request, $response, $args)
    {
        //экшн создания нового альбома
        $form = new AlbumForm();

        // Проверяем, отправил ли пользователь форму.
        if ($request->isPost()) {
            //выгружаем данные отправленные пользователем через POST
            $data = $request->getParsedBody();
            //dump($data); die();
            //Передаем данные в объект класса формы
            $form->setData($data);

            //вручную валидируем телефон +7 (999) 888-77-66
            $phone=$data['phone'];
            $isGood=substr($phone,0,4)==='+7 (' && substr($phone,7,2)===') ' && $phone[12]==='-' && $phone[15]==='-'
                && is_numeric($phone[4].$phone[5].$phone[6].$phone[9].$phone[10].$phone[11].$phone[13].$phone[14].$phone[16].$phone[17]);

            if ($form->isValid() && $isGood) {
                //если форма валидная, то создаем объект через менеджера объектов
                $this->albumManager->createAlbum($data);
                return $response=$response->withRedirect('//slimphp/albums');
            }
        }

        //если POST-отправки не было, то отдаем пользователю пустую форму
        return $this->renderer->render($response, 'create.phtml', ['viewModel'=>new ViewModel(['form'=>$form])]);

    }

    public function createAction1($request, $response, $args)
    {
        //экшн создания нового альбома


        // Проверяем, отправил ли пользователь форму.
        if ($request->isPost()) {
            //выгружаем данные отправленные пользователем через POST
            $data = $request->post();

            //вручную валидируем телефон +7 (999) 888-77-66
            $phone=$data['phone'];
            $isGood=substr($phone,0,4)==='+7 (' && substr($phone,7,2)===') ' && $phone[12]==='-' && $phone[15]==='-'
                && is_numeric($phone[4].$phone[5].$phone[6].$phone[9].$phone[10].$phone[11].$phone[13].$phone[14].$phone[16].$phone[17]);

            if ($isGood) {
                //если форма валидная, то создаем объект через менеджера объектов
                $this->albumManager->createAlbum($data);
                return $response=$response->withRedirect();
            }
        }

        //если POST-отправки не было, то отдаем пользователю пустую форму
        return $this->renderer->render($response, 'create.phtml', ['form'=>null]);
    }
/*
    public function editAction($request, $response, $args)
    {
        //экшн редактирования описания к альбому
        $form=new AlbumForm();

        //узнаем idAlbum для редактирования
        $idAlbum=$this->params()->fromRoute('idAlbum',-1);

        // Находим существующий альбом в базе данных.
        $album = $this->entityManager->getRepository(Album::class)->findOneById($idAlbum);
        if ($album == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // отправил ли юзер POST-запрос с формы
        if ($this->getRequest()->isPost()) {
            // Получаем POST-данные.
            $data = $this->params()->fromPost();
            // Заполняем форму данными.
            $form->setData($data);

            //вручную валидируем телефон +7 (999) 888-77-66
            $phone=$data['phone'];
            $isGood=substr($phone,0,4)==='+7 (' && substr($phone,7,2)===') ' && $phone[12]==='-' && $phone[15]==='-'
                && is_numeric($phone[4].$phone[5].$phone[6].$phone[9].$phone[10].$phone[11].$phone[13].$phone[14].$phone[16].$phone[17]);

            if ($form->isValid() && $isGood) {
                // Получаем валидированные данные формы.
                $data = $form->getData();
                $this->albumManager->editAlbum($album, $data);

                // Перенаправляем пользователя на страницу c альбомами.
                return $this->redirect()->toRoute('albums');
            }
        } else { //иначе готовим форму для юзера, чтобы ее поля содержали данные альбома
            $data = [
                'label' => $album->getLabel(),
                'note' => $album->getNote(),
                'authorName' => $album->getAuthor()->getName(),
                'phone' => $album->getAuthor()->getPhone(),
                'email'=>$album->getAuthor()->getEmail()
            ];
            $form->setData($data);
        }

        // Визуализируем шаблон представления.
        return new ViewModel([
            'form' => $form,
            'album' => $album
        ]);
    }

    public function viewAction($request, $response, $args)
    {
        //экшн просмотра альбома или фотографии в альбоме + paginator

        //фотографий на странице
        $photosPerPage=2;

        //просматриваемая страница
        $page = $this->params()->fromQuery('page', 1);

        $viewDiap=$this->params()->fromRoute('viewDiap',-1);
        $start_end=explode('-',$viewDiap);
        $startDiap=$start_end[0];
        $endDiap=$start_end[1];


        //узнаем idAlbum для просмотра
        $idAlbum=$this->params()->fromRoute('idAlbum',-1);

        // Находим существующий альбом в базе данных.
        $album = $this->entityManager->getRepository(Album::class)->findOneById($idAlbum);


        // @var \Doctrine\ORM\Query $photos



        $photos = $this->entityManager->getRepository(Photo::class)->findPhotosByAlbumId($idAlbum);
        //$numPhotos=count($album->getPhotos()->toArray());
        //dump($photos->execute());
        //die();


        if($album == null){
            $this->getResponse()->setStatusCode(404);
            return;
        }

        //зендовский пагинатор оч.общий, с пом.адаптера мы предоставляем ему данные об сущностях
        //то есть сообщаем ему о фотках для пагинации
        $adapter = new DoctrineAdapter(new ORMPaginator($photos,false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($photosPerPage);
        $paginator->setCurrentPageNumber($page);
        dump($page);

        //return new ViewModel(['album'=>$album]);
        //вместо всех фоток альбома, передаем фотки конретной страницы за счет пагинатора
        return new ViewModel(['album'=>$album, 'photos'=> $paginator, 'albumManager'=>$this->albumManager]);
    }

    public function deleteAction($request, $response, $args)
    {
        //экшн удаления альбома
        $idAblum=$this->params()->fromRoute('idAlbum',-1);
        $album=$this->entityManager->getRepository(Album::class)->findOneById($idAblum);

        if($album == null){
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->albumManager->deleteAlbum($album);
        return $this->redirect()->toRoute('albums');
    }

    public function addPhotoAction($request, $response, $args)
    {
        //экшн добавления фотографии с первоначальным выбором альбома с пом.селектора

        $form=new PhotoForm();

        // Проверяем, отправил ли пользователь форму.
        if($this->getRequest()->isPost()){
            //выгружаем данные отправленные пользователем
            $request = $this->getRequest();
            //объединяем данные отправленные через POST и сам загруженный файл (_POST_ и _FILES_)
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            //узнаем idAlbum для редактирования (какой select был выбран)
            $idAlbum=$data['idAlbum'];
            // Находим существующий альбом в базе данных.
            $album = $this->entityManager->getRepository(Album::class)->findOneById($idAlbum);

            //Передаем данные в объект класса формы
            $form->setData($data);

            if ($form->isValid()){
                //если форма валидная, то помещаем файл в каталог назначения

                //потому что метод getData() запустит  для формы фильтр RenameUpload,
                // который перемещает выгруженный на сервер файл в его постоянный каталог.
                $data1=$form->getData();
                //dump($data1);//там будет имя файлика после аплода
                $this->albumManager->addPhoto($album,$data1);

                return $this->redirect()->toRoute('albums/alb',['idAlbum'=>$album->getId()]);
            }
        }


        //cоздаем запрос на языке DSQL через методы entityManager'a
        //далее эти альбомы альбомы отправятся в селектор вьюшки
        $query = $this->entityManager->createQuery('SELECT u FROM Gallery\Entity\Album AS u ORDER BY u.id ASC');
        $albums = $query->getResult(); //вернется массив с объектками
        //если POST-отправки не было, то отдаем пользователю пустую форму и альбомы для селектора
        return new ViewModel(['form'=>$form, 'albums'=>$albums]);
    }

    public function newPhotoAction($request, $response, $args)
    {
        //экшн добавления фотографии в выбранный альбом
        //узнаем idAlbum для редактирования
        $idAlbum=$this->params()->fromRoute('idAlbum',-1);
        // Находим существующий альбом в базе данных.
        $album = $this->entityManager->getRepository(Album::class)->findOneById($idAlbum);

        $form=new PhotoForm();

        // Проверяем, отправил ли пользователь форму.
        if($this->getRequest()->isPost()){
            //выгружаем данные отправленные пользователем
            $request = $this->getRequest();
            //объединяем данные отправленные через POST и сам загруженный файл (_POST_ и _FILES_)
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            //Еще можно получить один конкретный элемент массива $_FILES.
            //        $files = $this->params()->fromFiles('file');

            //Передаем данные в объект класса формы
            $form->setData($data);
            $form->isValid();

            if ($form->isValid()){//запускаем валидацию данных
                //если форма валидная, то помещаем файл в каталог назначения

                //потому что метод getData() запустит  для формы фильтр RenameUpload,
                // который перемещает выгруженный на сервер файл в его постоянный каталог.
                $data1=$form->getData();
                //dump($data1);//мб там будет имя файлика после аплода
                $this->albumManager->addPhoto($album,$data1);

                return $this->redirect()->toRoute('albums/alb',['idAlbum'=>$album->getId()]);
            }
        }
        //если POST-отправки не было, то отдаем пользователю пустую форму
        return new ViewModel(['form'=>$form, 'album'=>$album]);
    }

    public function showPhotoAction($request, $response, $args)
    {
        $idPhoto=$this->params()->fromRoute('idPhoto');
        $idAlbum=$this->params()->fromRoute('idAlbum');
        $album=$this->entityManager->getRepository(Album::class)->findOneById($idAlbum);
        if ($album == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $photo=$this->entityManager->getRepository(Photo::class)->findOneById($idPhoto);
        if ($photo == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel(['photo'=>$photo, 'album'=>$album]);
    }

    public function editPhotoAction($request, $response, $args)
    {
        //экшн редактирования описания к альбому
        $form=new PhotoForm();
        $form->getInputFilter()->get('file')->setRequired(false);//отключаем поле,чтобы пройти валидацию


        //узнаем idPhotoдля редактирования
        $idPhoto=$this->params()->fromRoute('idPhoto',-1);

        // Находим существующее фото в базе данных.
        $photo = $this->entityManager->getRepository(Photo::class)->findOneById($idPhoto);
        if ($photo == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // отправил ли юзер POST-запрос с формы
        if ($this->getRequest()->isPost()) {
            // Получаем POST-данные.
            $data = $this->params()->fromPost();
            // Заполняем форму данными.
            $form->setData($data);

            if ($form->isValid()) {
                // Получаем валидированные данные формы.
                $data = $form->getData();
                $this->albumManager->editPhoto($photo, $data);

                $idAlbum=$this->params()->fromRoute('idAlbum',-1);
                // Перенаправляем пользователя на страницу фотографии.
                return $this->redirect()->toRoute('albums/alb',['idAlbum'=>$idAlbum]);
            }
        } else { //иначе готовим форму для юзера, чтобы ее поля содержали данные альбома
            $data = [
                'title' => $photo->getTitle(),
                'geo' => $photo->getGeo(),
            ];
            $form->setData($data);
        }

        // Визуализируем шаблон представления.
        return new ViewModel([
            'form' => $form,
            'photo' => $photo
        ]);
    }

    public function deletePhotoAction($request, $response, $args)
    {
        //экшн удаления фотографии
        $idPhoto=$this->params()->fromRoute('idPhoto',-1);
        $idAlbum=$this->params()->fromRoute('idAlbum',-1);
        $photo=$this->entityManager->getRepository(Photo::class)->findOneById($idPhoto);

        if($photo == null){
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->albumManager->deletePhoto($photo);
        return $this->redirect()->toRoute('albums/alb',['idAlbum'=>$idAlbum, 'action'=>'view']);
    }




*/
}