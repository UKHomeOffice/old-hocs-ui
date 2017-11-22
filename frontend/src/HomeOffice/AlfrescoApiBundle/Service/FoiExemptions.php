<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class FoiExemptions
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
final class FoiExemptions extends ConstantHelper
{
    // EIR exemptions
    const EIR124A = 'EIR124a';
    const EIR124B = 'EIR124b';
    const EIR124C = 'EIR124c';
    const EIR124D = 'EIR124d';
    const EIR124E = 'EIR124e';
    const EIR125A = 'EIR125a';
    const EIR125B = 'EIR125b';
    const EIR125C = 'EIR125c';
    const EIR125D = 'EIR125d';
    const EIR125E = 'EIR125e';
    const EIR125F = 'EIR125f';
    const EIR125G = 'EIR125g';

    // FOI exemptions
    const SECTION_21_1 = 'Section 21(1)';
    const SECTION_22_1 = 'Section 22(1)';
    const SECTION_22_2_NCND = 'Section 22(2) (NCND)';
    const SECTION_23_1 = 'Section 23(1)';
    const SECTION_23_5_NCND = 'Section 23(5) (NCND)';
    const SECTION_24_1 = 'Section 24(1)';
    const SECTION_24_2_NCND = 'Section 24(2) (NCND)';
    const SECTION_26_1 = 'Section 26(1)';
    const SECTION_26_3_NCND = 'Section 26(3) (NCND)';
    const SECTION_27_1 = 'Section 27(1)';
    const SECTION_27_2 = 'Section 27(2)';
    const SECTION_27_4_NCND = 'Section 27(4) (NCND)';
    const SECTION_28_1 = 'Section 28(1)';
    const SECTION_28_3_NCND = 'Section 28(3) (NCND)';
    const SECTION_29_1 = 'Section 29(1)';
    const SECTION_29_2_NCND = 'Section 29(2) (NCND)';
    const SECTION_30_1 = 'Section 30(1)';
    const SECTION_30_2 = 'Section 30(2)';
    const SECTION_30_3_NCND = 'Section 30(3) (NCND)';
    const SECTION_31_1 = 'Section 31(1)';
    const SECTION_31_3_NCND = 'Section 31(3) (NCND)';
    const SECTION_32_1 = 'Section 32(1)';
    const SECTION_32_2 = 'Section 32(2)';
    const SECTION_32_3_NCND = 'Section 32(3) (NCND)';
    const SECTION_33_1 = 'Section 33(1)';
    const SECTION_33_2 = 'Section 33(2)';
    const SECTION_33_3_NCND = 'Section 33(3) (NCND)';
    const SECTION_34_1 = 'Section 34(1)';
    const SECTION_34_2 = 'Section 34(2)';
    const SECTION_35_1 = 'Section 35(1)';
    const SECTION_35_3_NCND = 'Section 35(3) (NCND)';
    const SECTION_36_2 = 'Section 36(2)';
    const SECTION_36_3_NCND = 'Section 36(3) (NCND)';
    const SECTION_37_1 = 'Section 37(1)';
    const SECTION_37_2_NCND = 'Section 37(2) (NCND)';
    const SECTION_38_1 = 'Section 38(1)';
    const SECTION_38_2_NCND = 'Section 38(2) (NCND)';
    const SECTION_39_1 = 'Section 39(1)';
    const SECTION_39_2_NCND = 'Section 39(2) (NCND)';
    const SECTION_40_1 = 'Section 40(1)';
    const SECTION_40_2 = 'Section 40(2)';
    const SECTION_40_5_NCND = 'Section 40(5) (NCND)';
    const SECTION_41_1 = 'Section 41(1)';
    const SECTION_41_2_NCND = 'Section 41(2) (NCND)';
    const SECTION_42_1 = 'Section 42(1)';
    const SECTION_42_2_NCND = 'Section 42(2) (NCND)';
    const SECTION_43_1 = 'Section 43(1)';
    const SECTION_43_2 = 'Section 43(2)';
    const SECTION_43_3_NCND = 'Section 43(3) (NCND)';
    const SECTION_44_1 = 'Section 44(1)';
    const SECTION_44_2_NCND = 'Section 44(2) (NCND)';

    // FOI PIT qualified exemptions
    const SECTION_21   = '21. Information accessible to applicant by other means';
    const SECTION_22   = '22. Information intended for future publication';
    const SECTION_22_A = '22A. Research intended for future publication';
    const SECTION_23   = '23. Information supplied by, or relating to, bodies dealing with security matters';
    const SECTION_24   = '24. National security';
    const SECTION_26   = '26. Defence';
    const SECTION_27   = '27. International relations';
    const SECTION_28   = '28. Relations within the United Kingdom';
    const SECTION_29   = '29. The economy';
    const SECTION_30   = '30. Investigations and proceedings conducted by public authorities';
    const SECTION_31   = '31. Law enforcement';
    const SECTION_32   = '32. Court records, etc';
    const SECTION_33   = '33. Audit functions';
    const SECTION_34   = '34. Parliamentary privilege';
    const SECTION_35   = '35. Formulation of government policy, etc';
    const SECTION_36   = '36. Prejudice to effective conduct of public affairs';
    const SECTION_37   = '37. Communications with Her Majesty, etc. and honours';
    const SECTION_38   = '38. Health and safety';
    const SECTION_39   = '39. Environmental information';
    const SECTION_40   = '40. Personal information';
    const SECTION_41   = '41. Information provided on confidence';
    const SECTION_42   = '42. Legal professional privilege';
    const SECTION_43   = '43. Commercial interests';
    const SECTION_44   = '44. Prohibitions on disclosure';

    // EIR PIT qualified exemptions
    const EIR7_1 = 'EIR7(1)';

    /**
     * Get PIT Qualified Exemptions
     *
     * @param bool $isEir
     *
     * @return array
     */
    public static function getPitQualifiedExemptions($isEir = false)
    {
        if ($isEir === true) {
            return [
                self::EIR7_1,
            ];
        }

        return [
            self::SECTION_22,
            self::SECTION_22_A,
            self::SECTION_24,
            self::SECTION_26,
            self::SECTION_27,
            self::SECTION_28,
            self::SECTION_29,
            self::SECTION_30,
            self::SECTION_31,
            self::SECTION_33,
            self::SECTION_35,
            self::SECTION_36,
            self::SECTION_37,
            self::SECTION_38,
            self::SECTION_39,
            self::SECTION_42,
            self::SECTION_43,
        ];
    }
}
