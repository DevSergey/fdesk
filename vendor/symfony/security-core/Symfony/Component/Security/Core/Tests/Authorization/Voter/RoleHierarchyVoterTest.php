<?php
namespace Symfony\Component\Security\Core\Tests\Authorization\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
class RoleHierarchyVoterTest extends RoleVoterTest
{
    public function testVote($roles, $attributes, $expected)
    {
        $voter = new RoleHierarchyVoter(new RoleHierarchy(array('ROLE_FOO' => array('ROLE_FOOBAR'))));
        $this->assertSame($expected, $voter->vote($this->getToken($roles), null, $attributes));
    }
    public function getVoteTests()
    {
        return array_merge(parent::getVoteTests(), array(
            array(array('ROLE_FOO'), array('ROLE_FOOBAR'), VoterInterface::ACCESS_GRANTED),
        ));
    }
}