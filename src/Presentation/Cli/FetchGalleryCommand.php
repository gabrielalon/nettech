<?php

namespace App\Presentation\Cli;

use App\Application\Gallery\Fetch\FetcherRegistry;
use App\Application\Gallery\Service\GalleryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchGalleryCommand extends Command
{
    protected static $defaultName = 'app:fetch-gallery';

    /** @var FetcherRegistry */
    private $fetcherRegistry;

    /** @var GalleryService */
    private $galleryService;

    /**
     * FetchGalleryCommand constructor.
     * @param FetcherRegistry $fetcherRegistry
     * @param GalleryService $galleryService
     */
    public function __construct(
        FetcherRegistry $fetcherRegistry,
        GalleryService $galleryService
    ) {
        $this->fetcherRegistry = $fetcherRegistry;
        $this->galleryService = $galleryService;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setDescription('Fetches gallery.')
            ->setHelp('This command allows you to fetch a gallery...')
            ->addArgument('service', InputArgument::REQUIRED, 'Gallery service')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Gallery Fetcher',
            '===============',
            '',
        ]);

        try {
            $galleryFetcher = $this->fetcherRegistry->get($input->getArgument('service'));
        } catch (\Exception $e) {
            $output->writeln([
                $e->getMessage(),
                ''
            ]);

            return 0;
        }

        foreach ($galleryFetcher->galleries() as $name => $assets) {
            try {
                $gallery = $this->galleryService->create($name, $galleryFetcher->name(), $assets);

                $output->writeln([
                    'Successfully created gallery: ' . $gallery->identifier(),
                    'Assets count: ' . count($assets),
                    ''
                ]);
            }
            catch (\Exception $e) {
                $output->writeln([
                    'Gallery not created: ' . $name,
                    'Assets count: ' . count($assets),
                    ''
                ]);
            }
        }
        return 1;
    }
}