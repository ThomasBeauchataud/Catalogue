<?php


namespace App\Controller;


use App\Entity\Item;
use App\Repository\ClientRepository;
use App\Service\Item\ItemCreationList;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/item", name="item_")
 */
class ItemController extends AbstractController
{

    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @var ClientRepository
     */
    protected ClientRepository $clientRepository;

    /**
     * ItemController constructor.
     * @param ValidatorInterface $validator
     * @param ClientRepository $clientRepository
     */
    public function __construct(ValidatorInterface $validator, ClientRepository $clientRepository)
    {
        $this->validator = $validator;
        $this->clientRepository = $clientRepository;
    }


    /**
     * @Route("", name="select", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function select(Request $request): Response
    {
        var_dump($request->getClientIp());
        $getPrivate = $request->query->has("private");
        $items = array_map(function (Item $item) {
            return $item->serialize();
        }, $getPrivate ?
            $this->em->getRepository(Item::class)->findPrivate() :
            $this->em->getRepository(Item::class)->findPublic()
        );
        return new Response(json_encode($items));
    }

    /**
     * @Route("", name="insert", methods={"PUT"})
     * @param Request $request
     * @return Response
     */
    public function insert(Request $request): Response
    {
        try {
            $client = $this->clientRepository->findOneBy(["ip" => $request->getClientIp()]);
            $itemCreationList = new ItemCreationList($client, $request->getContent());
            $envelope = $this->dispatchMessage($itemCreationList);
            $content = $envelope->last(HandledStamp::class)->getResult();
            return new Response(json_encode(array("code" =>"OK", "content" => $content)));
        } catch (Exception $e) {
            return new Response(json_encode(array("code" => "KO", "content" => $e->getMessage())));
        }
    }

}