<?php

namespace SMT\Tests;

use SMT\HookRegistry;
use SMT\Options;
use Title;

/**
 * @covers \SMT\HookRegistry
 * @group semantic-meta-tags
 *
 * @license GPL-2.0-or-later
 * @since 1.0
 *
 * @author mwjames
 */
class HookRegistryTest extends \PHPUnit\Framework\TestCase {

	public function testCanConstruct() {
		$store = $this->getMockBuilder( '\SMW\Store' )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$options = $this->getMockBuilder( '\SMT\Options' )
			->disableOriginalConstructor()
			->getMock();

		$this->assertInstanceOf(
			'\SMT\HookRegistry',
			new HookRegistry( $store, $options )
		);
	}

	public function testRegister() {
		$store = $this->getMockBuilder( '\SMW\Store' )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$configuration = [
			'metaTagsContentPropertySelector' => [],
			'metaTagsStaticContentDescriptor' => [],
			'metaTagsBlacklist' => [],
			'metaTagsFallbackUseForMultipleProperties' => false,
			'metaTagsMetaPropertyPrefixes' => []
		];

		$instance = new HookRegistry(
			$store,
			new Options( $configuration )
		);

		$this->doTestRegisteredOutputPageParserOutputHandler( $instance );
	}

	public function doTestRegisteredOutputPageParserOutputHandler( $instance ) {
		$handler = 'OutputPageParserOutput';

		$title = Title::newFromText( __METHOD__ );

		$context = $this->getMockBuilder( '\IContextSource' )
			->disableOriginalConstructor()
			->getMock();

		$context->expects( $this->any() )
			->method( 'getActionName' )
			->willReturn( 'view' );

		$outputPage = $this->getMockBuilder( '\OutputPage' )
			->disableOriginalConstructor()
			->getMock();

		$outputPage->expects( $this->atLeastOnce() )
			->method( 'getTitle' )
			->willReturn( $title );

		$outputPage->expects( $this->atLeastOnce() )
			->method( 'getContext' )
			->willReturn( $context );

		$parserOutput = $this->getMockBuilder( '\ParserOutput' )
			->disableOriginalConstructor()
			->getMock();

		$this->assertTrue(
			$instance->isRegistered( $handler )
		);

		$this->assertThatHookIsExcutable(
			$instance->getHandlerFor( $handler ),
			[ &$outputPage, $parserOutput ]
		);
	}

	private function assertThatHookIsExcutable( \Closure $handler, $arguments ) {
		$this->assertIsBool(

			call_user_func_array( $handler, $arguments )
		);
	}

}
