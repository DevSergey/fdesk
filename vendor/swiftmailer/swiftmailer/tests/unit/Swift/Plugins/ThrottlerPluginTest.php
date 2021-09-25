<?php
class Swift_Plugins_ThrottlerPluginTest extends \SwiftMailerTestCase
{
    public function testBytesPerMinuteThrottling()
    {
        $sleeper = $this->_createSleeper();
        $timer = $this->_createTimer();
        $plugin = new Swift_Plugins_ThrottlerPlugin(
            10000000, Swift_Plugins_ThrottlerPlugin::BYTES_PER_MINUTE,
            $sleeper, $timer
            );
        $timer->shouldReceive('getTimestamp')->once()->andReturn(0);
        $timer->shouldReceive('getTimestamp')->once()->andReturn(1); 
        $timer->shouldReceive('getTimestamp')->once()->andReturn(1); 
        $timer->shouldReceive('getTimestamp')->once()->andReturn(2); 
        $timer->shouldReceive('getTimestamp')->once()->andReturn(2); 
        $sleeper->shouldReceive('sleep')->twice()->with(1);
        $message = $this->_createMessageWithByteCount(100000); 
        $evt = $this->_createSendEvent($message);
        for ($i = 0; $i < 5; ++$i) {
            $plugin->beforeSendPerformed($evt);
            $plugin->sendPerformed($evt);
        }
    }
    public function testMessagesPerMinuteThrottling()
    {
        $sleeper = $this->_createSleeper();
        $timer = $this->_createTimer();
        $plugin = new Swift_Plugins_ThrottlerPlugin(
            60, Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE,
            $sleeper, $timer
            );
        $timer->shouldReceive('getTimestamp')->once()->andReturn(0);
        $timer->shouldReceive('getTimestamp')->once()->andReturn(0); 
        $timer->shouldReceive('getTimestamp')->once()->andReturn(2); 
        $timer->shouldReceive('getTimestamp')->once()->andReturn(2); 
        $timer->shouldReceive('getTimestamp')->once()->andReturn(4); 
        $sleeper->shouldReceive('sleep')->twice()->with(1);
        $message = $this->_createMessageWithByteCount(10);
        $evt = $this->_createSendEvent($message);
        for ($i = 0; $i < 5; ++$i) {
            $plugin->beforeSendPerformed($evt);
            $plugin->sendPerformed($evt);
        }
    }
    private function _createSleeper()
    {
        return $this->getMockery('Swift_Plugins_Sleeper');
    }
    private function _createTimer()
    {
        return $this->getMockery('Swift_Plugins_Timer');
    }
    private function _createMessageWithByteCount($bytes)
    {
        $msg = $this->getMockery('Swift_Mime_Message');
        $msg->shouldReceive('toByteStream')
            ->zeroOrMoreTimes()
            ->andReturnUsing(function ($is) use ($bytes) {
                for ($i = 0; $i < $bytes; ++$i) {
                    $is->write('x');
                }
            });
        return $msg;
    }
    private function _createSendEvent($message)
    {
        $evt = $this->getMockery('Swift_Events_SendEvent');
        $evt->shouldReceive('getMessage')
            ->zeroOrMoreTimes()
            ->andReturn($message);
        return $evt;
    }
}
