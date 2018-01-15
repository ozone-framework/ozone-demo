<?php

namespace App\Modules\News\Repositories {

    use App\Modules\Example\Entity\Example;
    use Core\AbstractResource;

    class NewsRepository extends AbstractResource
    {

        public function findAll($page = 1, $limit = 12, $filter = [])
        {
            $limit = empty($limit) ? getenv('DEF_LIMIT', 12) : $limit;

            $pages = ($page == '') ? 1 : $page;
            $offset = ($pages - 1) * $limit;

            $qb = $this->em->createQueryBuilder();


            $qb->select('e')
                ->from(Example::class, 'e');

            if (array_key_exists('title', $filter) and $filter['title'] != '') {
                $qb->where('e.title LIKE :title')
                    ->setParameter('title', '%' . $filter['title'] . '%');
            }

            // Filter by Status
            $status = empty($filter['status']) ? [0, 1] : $filter['status'];
            $qb->andWhere('e.status IN (:status)')
                ->setParameter('status', $status);

            // Filter by Date
            if (array_key_exists('from', $filter) and $filter['to'] != '') {

                $qb->andWhere('e.created_at BETWEEN :from AND :to')
                    ->setParameter('from', $filter['from'])
                    ->setParameter('to', $filter['to']);
            }

            // Sort By Order
            $sort = empty($filter['sort']) ? 'e.id' : $filter['sort'];
            $order = empty($filter['order']) ? 'DESC' : $filter['order'];
            $qb->orderBy('e.' . $sort, $order);

            $qb->setMaxResults($limit);
            $qb->setFirstResult($offset);

            return $qb->getQuery()->getResult();
        }

        public function save($data)
        {
            try {

                $this->em->persist($data);
                $this->em->flush();

                return $data->getId();

            } catch (\Throwable $t) {
                return $t->getMessage();
            }
        }

        public function update($data)
        {
            try {
                $this->em->merge($data);
                $this->em->flush();
                return true;

            } catch (\Throwable $t) {
                return false;
            }

        }

        public function delete($datas)
        {
            try {

                if (is_array($datas)) {
                    foreach ($datas as $data) {
                        $this->em->remove($data);
                    }
                } else {
                    $this->em->remove($datas);
                }

                $this->em->flush();
                return true;

            } catch (\Throwable $t) {
                return false;
            }
        }

        public function find($id)
        {
            $example = $this->em->getRepository(Example::class);
            return $example->find($id);
        }
    }
}
