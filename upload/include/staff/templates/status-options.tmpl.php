<?php
global $thisstaff, $ticket;
// Map states to actions
$actions= array(
        'closed' => array(
            'icon'  => 'icon-ok-circle',
            'action' => 'close',
            'href' => 'tickets.php'
            ),
        'open' => array(
            'icon'  => 'icon-undo',
            'action' => 'reopen'
            ),
		'resolved' => array(
            'icon'  => 'icon-check-sign',
            'action' => 'resolved'
            ),
		'progress' => array(
            'icon'  => 'icon-time',
            'action' => 'progress'
            ),
        );

$states = array('open','progress','resolved');
if ($thisstaff->getRole($ticket ? $ticket->getDeptId() : null)->hasPerm(TicketModel::PERM_CLOSE)
        && (!$ticket || !$ticket->getMissingRequiredFields()))
    $states = array_merge($states, array('closed'));

$statusId = $ticket ? $ticket->getStatusId() : 0;
$nextStatuses = array();
foreach (TicketStatusList::getStatuses(
            array('states' => $states)) as $status) {
    if (!isset($actions[$status->getState()])
            || $statusId == $status->getId())
        continue;
    $nextStatuses[] = $status;
}

if (!$nextStatuses)
    return;
?>

<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle action-button" data-toggle="dropdown" title="<?php echo __('Change Status'); ?>" aria-haspopup="true" aria-expanded="false">
        <i class="tickets-action icon-flag"></i>
    </button>
    <ul id="action-dropdown-statuses" class="dropdown-menu">
<?php foreach ($nextStatuses as $status) { ?>
        <li>
            <a class="no-pjax <?php
                echo $ticket? 'ticket-action' : 'tickets-action'; ?>"
                href="<?php
                    echo sprintf('#%s/status/%s/%d',
                            $ticket ? ('tickets/'.$ticket->getId()) : 'tickets',
                            $actions[$status->getState()]['action'],
                            $status->getId()); ?>"
                <?php
                if (isset($actions[$status->getState()]['href']))
                    echo sprintf('data-redirect="%s"',
                            $actions[$status->getState()]['href']);

                ?>
                ><i class="<?php
                        echo $actions[$status->getState()]['icon'] ?: 'icon-tag';
                    ?>"></i> <?php
                        echo __($status->getName()); ?></a>
        </li>
    <?php
    } ?>
    </ul>
</div>

