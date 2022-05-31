<?php 

namespace Deviam\Bancard\Operations;

use Illuminate\Http\Client\Response;
use Deviam\Bancard\Petitions\{Petition, PreAuthorizationConfirm as PreAuthorizationConfirmPetition};

class PreAuthorizationConfirm extends Operation
{
    private static string $resource = 'vpos/api/0.3/preauthorizations/confirm';

    private string $shopProcessId;
    private float $amount;

    public function __construct(string $shopProcessId, float $amount = 0)
    {
        $this->shopProcessId = $shopProcessId;
        $this->amount = $amount;
    }

    protected static function getResource(): string
    {
        return self::$resource;
    }

    protected function getPetition(): Petition
    {
        return new PreAuthorizationConfirmPetition($this->shopProcessId, $this->amount);
    }

    protected function handleSuccess(Petition $petition, Response $response): void
    {
        $data = $response->json();
        $petition->handlePayload($data['operation']);
    }
}
