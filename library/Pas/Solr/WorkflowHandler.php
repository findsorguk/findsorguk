<?php

class Pas_Solr_WorkflowHandler
{
    protected static string $publishedWorkflow = "3";

    protected static array $workflow
        = array(
            "validated"   =>
                array(
                    "value"        => "4",
                    "allowedRoles" => array(
                        'fa',
                        'flos',
                        'hero',
                        'hoard',
                        'admin',
                        'public',
                        'member',
                        'treasure',
                        'research'
                    )
                ),
            "review"      => array(
                "value"        => "2",
                "allowedRoles" => array(
                    'fa',
                    'flos',
                    'hoard',
                    'admin',
                    'treasure',
                )
            ),
            "quarantined" => array(
                "value"        => "1",
                "allowedRoles" => array(
                    'fa',
                    'flos',
                    'hoard',
                    'admin',
                    'treasure',
                )
            )
        );

    public function __invoke(string $role): string
    {
        $allowedWorkflows = $this->allowedWorkflow($role);
        return $this->generateWorkflowFilterQuery($allowedWorkflows);
    }

    /** Get array of workflows that user is allowed to view
     *
     * @param string $userRole
     *
     * @return string[]
     */
    protected function allowedWorkflow(string $userRole): array
    {
        $allowedWorkflows = array(self::$publishedWorkflow);

        //See if user is in allowed workflow roles
        foreach (self::$workflow as $role) {
            if (in_array($userRole, $role['allowedRoles'])) {
                $allowedWorkflows[] = $role['value']; //Add workflow value
            }
        }
        sort($allowedWorkflows);

        return $allowedWorkflows;
    }

    /** Generate Solr workflow filter query
     *
     * @param $allowedWorkflows
     *
     * @return string
     */
    protected function generateWorkflowFilterQuery($allowedWorkflows): string
    {
        if (count($allowedWorkflows) > 1) {
            return "workflow:[" . $allowedWorkflows[0] . " TO " .
                array_pop(
                    $allowedWorkflows
                ) .
                "]";
        }
        return "workflow:" . $allowedWorkflows[0];
    }

}
