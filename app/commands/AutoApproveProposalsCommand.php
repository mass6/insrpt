<?php

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Insight\ProductProposals\Commands\AutomaticProposalApprovalCommand;
use Insight\ProductProposals\Commands\AutomaticProposalApprovalCommandHandler;
use Insight\ProductProposals\ProductProposal;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class AutoApproveProposalsCommand
 */
class AutoApproveProposalsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'insight:auto-approve-proposals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically approves product proposals after a configured timeout has elapsed.';

    /**
     * @var
     */
    protected $defaultTimeoutInHours;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if ( ! $productProposals = ProductProposal::where('state', 'APP')->get()) {
            $this->info('There are no Proposals currently Pending Approval.');

            return false;
        }

        $this->info('# of Proposals Pending Approval: ' . count($productProposals) . "\r\n");

        $approvedProposals = $this->processCustomerProposals($productProposals->groupBy('company_id'));

        $this->logResults($approvedProposals);
    }


    /**
     * @param $proposalsGroupedByCompanyId
     *
     * @return array
     */
    private function processCustomerProposals($proposalsGroupedByCompanyId)
    {
        $jobResults = [ ];

        foreach ($proposalsGroupedByCompanyId as $company_id => $proposals) {
            $company = $proposals[0]->company;

            // skip if auto approvals are not configured for Customer
            if ( ! $timeoutWindowInHours = $company->settings()->timeoutWindow()) {
                continue;
            }

            // Approve any proposals past timeout window. If any proposals were approved,
            // add the array of approved proposal_id's to the jobResults array
            if ($proposalsApproved = $this->approveProposalsPastTheTimeoutWindow($proposals, $timeoutWindowInHours)) {
                $jobResults[$company->name] = $proposalsApproved;
            }

        }

        return $jobResults;
    }


    /**
     * Checks proposal against timeout window
     *
     * @param $proposals
     * @param $timeoutWindowInHours
     *
     * @return array
     */
    protected function approveProposalsPastTheTimeoutWindow($proposals, $timeoutWindowInHours)
    {
        $proposalsApproved = [ ];
        $now = Carbon::now();

        foreach ($proposals as $proposal) {

            // Add defined timeout-hours to the date proposal was assigned to current approver
            $endOfTimeoutWindow = $proposal->assigned_at->addHours($timeoutWindowInHours);

            // Check if proposal timeout window has elapsed
            if ($now->gt($endOfTimeoutWindow)) {

                $handler = new AutomaticProposalApprovalCommandHandler;
                $handler->handle(new AutomaticProposalApprovalCommand($proposal));
                $proposalsApproved[] = $proposal->proposal_id;
            }
        }

        return $proposalsApproved;
    }


    /**
     * Log the approved proposals
     *
     * @param $approvedProposals
     */
    protected function logResults($approvedProposals)
    {
        if ($approvedProposals) {

            foreach ($approvedProposals as $companyName => $proposalsApproved) {
                Log::info(["\r\n" . 'Proposals automatically approved for: ' . $companyName => $proposalsApproved ]);
                $this->info('Proposals automatically approved for ' . $companyName . ':' . "\r");
                $this->info(implode("\r\n", $proposalsApproved) . "\r\n");
            }
        };
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            //array('example', InputArgument::REQUIRED, 'An example argument.'),
        ];
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            //array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        ];
    }

}
