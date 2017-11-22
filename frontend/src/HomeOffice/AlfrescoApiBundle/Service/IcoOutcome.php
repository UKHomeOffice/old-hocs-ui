<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class IcoOutcome
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
final class IcoOutcome extends ConstantHelper
{
    const HO_DECISION_UPHELD = 'HO Decision Upheld';
    const HO_DECISION_PARTIALLY_UPHELD = 'HO Decision Partially Upheld';
    const HO_DECISION_PARTIALLY_OVERTURNED = 'HO Decision Overturned';
    const INFORMALLY_RESOLVED = 'Informally resolved';
}
