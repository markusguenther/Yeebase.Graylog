<?php
namespace Yeebase\Graylog\Log;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Log\Logger;
use Yeebase\Graylog\GraylogService;

class GraylogLogger extends Logger
{
    /**
     * @Flow\Inject
     * @var GraylogService
     */
    protected $graylogService;

    /**
     * Writes information about the given exception to Graylog including the stacktrace.
     *
     * @param object $error \Exception or \Throwable
     * @param array $additionalData Additional data to log
     * @return void
     */
    public function logError($error, array $additionalData = [])
    {
        $this->getGraylogService()->logException($error);
        parent::logError($error, $additionalData);
    }

    /**
     * Returns an instance of the injected GraylogService (including a fallback to a manually instantiated instance
     * if Dependency Injection is not (yet) available)
     *
     * @return GraylogService
     */
    private function getGraylogService()
    {
        if ($this->graylogService instanceof GraylogService) {
            return $this->graylogService;
        } elseif ($this->graylogService instanceof DependencyProxy) {
            return $this->graylogService->_activateDependency();
        } else {
            return new GraylogService();
        }
    }
}
