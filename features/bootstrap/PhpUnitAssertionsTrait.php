<?php

trait PhpUnitAssertionsTrait
{

    /**
     * Asserts that an array has a specified key.
     *
     * @param mixed             $key
     * @param array|ArrayAccess $array
     * @param string            $message
     *
     * @since Method available since Release 3.0.0
     */
    protected function assertArrayHasKey($key, $array, $message = '')
    {
        return $this->phpUnit->assertArrayHasKey($key, $array, $message = '');
    }


    /**
     * Asserts that an array has a specified subset.
     *
     * @param array|ArrayAccess $subset
     * @param array|ArrayAccess $array
     * @param bool              $strict Check for object identity
     * @param string            $message
     *
     * @since Method available since Release 4.4.0
     */
    protected function assertArraySubset($subset, $array, $strict = false, $message = '')
    {
        return $this->phpUnit->assertArraySubset($subset, $array, $strict = false, $message = '');
    }


    /**
     * Asserts that an array does not have a specified key.
     *
     * @param mixed             $key
     * @param array|ArrayAccess $array
     * @param string            $message
     *
     * @since  Method available since Release 3.0.0
     */
    protected function assertArrayNotHasKey($key, $array, $message = '')
    {
        return $this->phpUnit->assertArrayNotHasKey($key, $array, $message = '');
    }


    /**
     * Asserts that a haystack contains a needle.
     *
     * @param mixed  $needle
     * @param mixed  $haystack
     * @param string $message
     * @param bool   $ignoreCase
     * @param bool   $checkForObjectIdentity
     * @param bool   $checkForNonObjectIdentity
     *
     * @since  Method available since Release 2.1.0
     */
    protected function assertContains(
        $needle,
        $haystack,
        $message = '',
        $ignoreCase = false,
        $checkForObjectIdentity = true,
        $checkForNonObjectIdentity = false
    ) {
        return $this->phpUnit->assertContains($needle, $haystack, $message = '', $ignoreCase = false,
            $checkForObjectIdentity = true, $checkForNonObjectIdentity = false);
    }


    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object contains a needle.
     *
     * @param mixed  $needle
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param string $message
     * @param bool   $ignoreCase
     * @param bool   $checkForObjectIdentity
     * @param bool   $checkForNonObjectIdentity
     *
     * @since  Method available since Release 3.0.0
     */
    protected function assertAttributeContains(
        $needle,
        $haystackAttributeName,
        $haystackClassOrObject,
        $message = '',
        $ignoreCase = false,
        $checkForObjectIdentity = true,
        $checkForNonObjectIdentity = false
    ) {
        return $this->phpUnit->assertAttributeContains($needle, $haystackAttributeName, $haystackClassOrObject,
            $message = '', $ignoreCase = false, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false);
    }


    /**
     * Asserts that a haystack does not contain a needle.
     *
     * @param mixed  $needle
     * @param mixed  $haystack
     * @param string $message
     * @param bool   $ignoreCase
     * @param bool   $checkForObjectIdentity
     * @param bool   $checkForNonObjectIdentity
     *
     * @since  Method available since Release 2.1.0
     */
    protected function assertNotContains(
        $needle,
        $haystack,
        $message = '',
        $ignoreCase = false,
        $checkForObjectIdentity = true,
        $checkForNonObjectIdentity = false
    ) {
        return $this->phpUnit->assertNotContains($needle, $haystack, $message = '', $ignoreCase = false,
            $checkForObjectIdentity = true, $checkForNonObjectIdentity = false);
    }


    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object does not contain a needle.
     *
     * @param mixed  $needle
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param string $message
     * @param bool   $ignoreCase
     * @param bool   $checkForObjectIdentity
     * @param bool   $checkForNonObjectIdentity
     *
     * @since  Method available since Release 3.0.0
     */
    protected function assertAttributeNotContains(
        $needle,
        $haystackAttributeName,
        $haystackClassOrObject,
        $message = '',
        $ignoreCase = false,
        $checkForObjectIdentity = true,
        $checkForNonObjectIdentity = false
    ) {
        return $this->phpUnit->assertAttributeNotContains($needle, $haystackAttributeName, $haystackClassOrObject,
            $message = '', $ignoreCase = false, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false);
    }


    /**
     * Asserts that a haystack contains only values of a given type.
     *
     * @param string $type
     * @param mixed  $haystack
     * @param bool   $isNativeType
     * @param string $message
     *
     * @since  Method available since Release 3.1.4
     */
    protected function assertContainsOnly($type, $haystack, $isNativeType = null, $message = '')
    {
        return $this->phpUnit->assertContainsOnly($type, $haystack, $isNativeType = null, $message = '');
    }


    /**
     * Asserts that a haystack contains only instances of a given classname
     *
     * @param string            $classname
     * @param array|Traversable $haystack
     * @param string            $message
     */
    protected function assertContainsOnlyInstancesOf($classname, $haystack, $message = '')
    {
        return $this->phpUnit->assertContainsOnlyInstancesOf($classname, $haystack, $message = '');
    }


    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object contains only values of a given type.
     *
     * @param string $type
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param bool   $isNativeType
     * @param string $message
     *
     * @since  Method available since Release 3.1.4
     */
    protected function assertAttributeContainsOnly(
        $type,
        $haystackAttributeName,
        $haystackClassOrObject,
        $isNativeType = null,
        $message = ''
    ) {
        return $this->phpUnit->assertAttributeContainsOnly($type, $haystackAttributeName, $haystackClassOrObject,
            $isNativeType = null, $message = '');
    }


    /**
     * Asserts that a haystack does not contain only values of a given type.
     *
     * @param string $type
     * @param mixed  $haystack
     * @param bool   $isNativeType
     * @param string $message
     *
     * @since  Method available since Release 3.1.4
     */
    protected function assertNotContainsOnly($type, $haystack, $isNativeType = null, $message = '')
    {
        return $this->phpUnit->assertNotContainsOnly($type, $haystack, $isNativeType = null, $message = '');
    }


    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object does not contain only values of a given
     * type.
     *
     * @param string $type
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param bool   $isNativeType
     * @param string $message
     *
     * @since  Method available since Release 3.1.4
     */
    protected function assertAttributeNotContainsOnly(
        $type,
        $haystackAttributeName,
        $haystackClassOrObject,
        $isNativeType = null,
        $message = ''
    ) {
        return $this->phpUnit->assertAttributeNotContainsOnly($type, $haystackAttributeName, $haystackClassOrObject,
            $isNativeType = null, $message = '');
    }


    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param int    $expectedCount
     * @param mixed  $haystack
     * @param string $message
     */
    protected function assertCount($expectedCount, $haystack, $message = '')
    {
        return $this->phpUnit->assertCount($expectedCount, $haystack, $message = '');
    }


    /**
     * Asserts the number of elements of an array, Countable or Traversable
     * that is stored in an attribute.
     *
     * @param int    $expectedCount
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param string $message
     *
     * @since Method available since Release 3.6.0
     */
    protected function assertAttributeCount(
        $expectedCount,
        $haystackAttributeName,
        $haystackClassOrObject,
        $message = ''
    ) {
        return $this->phpUnit->assertAttributeCount($expectedCount, $haystackAttributeName, $haystackClassOrObject,
            $message = '');
    }


    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param int    $expectedCount
     * @param mixed  $haystack
     * @param string $message
     */
    protected function assertNotCount($expectedCount, $haystack, $message = '')
    {
        return $this->phpUnit->assertNotCount($expectedCount, $haystack, $message = '');
    }


    /**
     * Asserts the number of elements of an array, Countable or Traversable
     * that is stored in an attribute.
     *
     * @param int    $expectedCount
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param string $message
     *
     * @since Method available since Release 3.6.0
     */
    protected function assertAttributeNotCount(
        $expectedCount,
        $haystackAttributeName,
        $haystackClassOrObject,
        $message = ''
    ) {
        return $this->phpUnit->assertAttributeNotCount($expectedCount, $haystackAttributeName, $haystackClassOrObject,
            $message = '');
    }


    /**
     * Asserts that two variables are equal.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     * @param float  $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     */
    protected function assertEquals(
        $expected,
        $actual,
        $message = '',
        $delta = 0.0,
        $maxDepth = 10,
        $canonicalize = false,
        $ignoreCase = false
    ) {
        return $this->phpUnit->assertEquals($expected, $actual, $message = '', $delta = 0.0, $maxDepth = 10,
            $canonicalize = false, $ignoreCase = false);
    }


    /**
     * Asserts that a variable is equal to an attribute of an object.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param string $actualClassOrObject
     * @param string $message
     * @param float  $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     */
    protected function assertAttributeEquals(
        $expected,
        $actualAttributeName,
        $actualClassOrObject,
        $message = '',
        $delta = 0.0,
        $maxDepth = 10,
        $canonicalize = false,
        $ignoreCase = false
    ) {
        return $this->phpUnit->assertAttributeEquals($expected, $actualAttributeName, $actualClassOrObject,
            $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false);
    }


    /**
     * Asserts that two variables are not equal.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     * @param float  $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     *
     * @since  Method available since Release 2.3.0
     */
    protected function assertNotEquals(
        $expected,
        $actual,
        $message = '',
        $delta = 0.0,
        $maxDepth = 10,
        $canonicalize = false,
        $ignoreCase = false
    ) {
        return $this->phpUnit->assertNotEquals($expected, $actual, $message = '', $delta = 0.0, $maxDepth = 10,
            $canonicalize = false, $ignoreCase = false);
    }


    /**
     * Asserts that a variable is not equal to an attribute of an object.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param string $actualClassOrObject
     * @param string $message
     * @param float  $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     */
    protected function assertAttributeNotEquals(
        $expected,
        $actualAttributeName,
        $actualClassOrObject,
        $message = '',
        $delta = 0.0,
        $maxDepth = 10,
        $canonicalize = false,
        $ignoreCase = false
    ) {
        return $this->phpUnit->assertAttributeNotEquals($expected, $actualAttributeName, $actualClassOrObject,
            $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false);
    }


    /**
     * Asserts that a variable is empty.
     *
     * @param  mixed  $actual
     * @param  string $message
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    protected function assertEmpty($actual, $message = '')
    {
        return $this->phpUnit->assertEmpty($actual, $message = '');
    }


    /**
     * Asserts that a static attribute of a class or an attribute of an object
     * is empty.
     *
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param string $message
     *
     * @since Method available since Release 3.5.0
     */
    protected function assertAttributeEmpty($haystackAttributeName, $haystackClassOrObject, $message = '')
    {
        return $this->phpUnit->assertAttributeEmpty($haystackAttributeName, $haystackClassOrObject, $message = '');
    }


    /**
     * Asserts that a variable is not empty.
     *
     * @param  mixed  $actual
     * @param  string $message
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    protected function assertNotEmpty($actual, $message = '')
    {
        return $this->phpUnit->assertNotEmpty($actual, $message = '');
    }


    /**
     * Asserts that a static attribute of a class or an attribute of an object
     * is not empty.
     *
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param string $message
     *
     * @since Method available since Release 3.5.0
     */
    protected function assertAttributeNotEmpty($haystackAttributeName, $haystackClassOrObject, $message = '')
    {
        return $this->phpUnit->assertAttributeNotEmpty($haystackAttributeName, $haystackClassOrObject, $message = '');
    }


    /**
     * Asserts that a value is greater than another value.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertGreaterThan($expected, $actual, $message = '')
    {
        return $this->phpUnit->assertGreaterThan($expected, $actual, $message = '');
    }


    /**
     * Asserts that an attribute is greater than another value.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param string $actualClassOrObject
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertAttributeGreaterThan($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        return $this->phpUnit->assertAttributeGreaterThan($expected, $actualAttributeName, $actualClassOrObject,
            $message = '');
    }


    /**
     * Asserts that a value is greater than or equal to another value.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertGreaterThanOrEqual($expected, $actual, $message = '')
    {
        return $this->phpUnit->assertGreaterThanOrEqual($expected, $actual, $message = '');
    }


    /**
     * Asserts that an attribute is greater than or equal to another value.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param string $actualClassOrObject
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertAttributeGreaterThanOrEqual(
        $expected,
        $actualAttributeName,
        $actualClassOrObject,
        $message = ''
    ) {
        return $this->phpUnit->assertAttributeGreaterThanOrEqual($expected, $actualAttributeName, $actualClassOrObject,
            $message = '');
    }


    /**
     * Asserts that a value is smaller than another value.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertLessThan($expected, $actual, $message = '')
    {
        return $this->phpUnit->assertLessThan($expected, $actual, $message = '');
    }


    /**
     * Asserts that an attribute is smaller than another value.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param string $actualClassOrObject
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertAttributeLessThan($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        return $this->phpUnit->assertAttributeLessThan($expected, $actualAttributeName, $actualClassOrObject,
            $message = '');
    }


    /**
     * Asserts that a value is smaller than or equal to another value.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertLessThanOrEqual($expected, $actual, $message = '')
    {
        return $this->phpUnit->assertLessThanOrEqual($expected, $actual, $message = '');
    }


    /**
     * Asserts that an attribute is smaller than or equal to another value.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param string $actualClassOrObject
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertAttributeLessThanOrEqual(
        $expected,
        $actualAttributeName,
        $actualClassOrObject,
        $message = ''
    ) {
        return $this->phpUnit->assertAttributeLessThanOrEqual($expected, $actualAttributeName, $actualClassOrObject,
            $message = '');
    }


    /**
     * Asserts that the contents of one file is equal to the contents of another
     * file.
     *
     * @param string $expected
     * @param string $actual
     * @param string $message
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     *
     * @since  Method available since Release 3.2.14
     */
    protected function assertFileEquals($expected, $actual, $message = '', $canonicalize = false, $ignoreCase = false)
    {
        return $this->phpUnit->assertFileEquals($expected, $actual, $message = '', $canonicalize = false,
            $ignoreCase = false);
    }


    /**
     * Asserts that the contents of one file is not equal to the contents of
     * another file.
     *
     * @param string $expected
     * @param string $actual
     * @param string $message
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     *
     * @since  Method available since Release 3.2.14
     */
    protected function assertFileNotEquals(
        $expected,
        $actual,
        $message = '',
        $canonicalize = false,
        $ignoreCase = false
    ) {
        return $this->phpUnit->assertFileNotEquals($expected, $actual, $message = '', $canonicalize = false,
            $ignoreCase = false);
    }


    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file.
     *
     * @param string $expectedFile
     * @param string $actualString
     * @param string $message
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     *
     * @since  Method available since Release 3.3.0
     */
    protected function assertStringEqualsFile(
        $expectedFile,
        $actualString,
        $message = '',
        $canonicalize = false,
        $ignoreCase = false
    ) {
        return $this->phpUnit->assertStringEqualsFile($expectedFile, $actualString, $message = '',
            $canonicalize = false, $ignoreCase = false);
    }


    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file.
     *
     * @param string $expectedFile
     * @param string $actualString
     * @param string $message
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     *
     * @since  Method available since Release 3.3.0
     */
    protected function assertStringNotEqualsFile(
        $expectedFile,
        $actualString,
        $message = '',
        $canonicalize = false,
        $ignoreCase = false
    ) {
        return $this->phpUnit->assertStringNotEqualsFile($expectedFile, $actualString, $message = '',
            $canonicalize = false, $ignoreCase = false);
    }


    /**
     * Asserts that a file exists.
     *
     * @param string $filename
     * @param string $message
     *
     * @since  Method available since Release 3.0.0
     */
    protected function assertFileExists($filename, $message = '')
    {
        return $this->phpUnit->assertFileExists($filename, $message = '');
    }


    /**
     * Asserts that a file does not exist.
     *
     * @param string $filename
     * @param string $message
     *
     * @since  Method available since Release 3.0.0
     */
    protected function assertFileNotExists($filename, $message = '')
    {
        return $this->phpUnit->assertFileNotExists($filename, $message = '');
    }


    /**
     * Asserts that a condition is true.
     *
     * @param  bool   $condition
     * @param  string $message
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    protected function assertTrue($condition, $message = '')
    {
        return $this->phpUnit->assertTrue($condition, $message = '');
    }


    /**
     * Asserts that a condition is not true.
     *
     * @param  bool   $condition
     * @param  string $message
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    protected function assertNotTrue($condition, $message = '')
    {
        return $this->phpUnit->assertNotTrue($condition, $message = '');
    }


    /**
     * Asserts that a condition is false.
     *
     * @param  bool   $condition
     * @param  string $message
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    protected function assertFalse($condition, $message = '')
    {
        return $this->phpUnit->assertFalse($condition, $message = '');
    }


    /**
     * Asserts that a condition is not false.
     *
     * @param  bool   $condition
     * @param  string $message
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    protected function assertNotFalse($condition, $message = '')
    {
        return $this->phpUnit->assertNotFalse($condition, $message = '');
    }


    /**
     * Asserts that a variable is not null.
     *
     * @param mixed  $actual
     * @param string $message
     */
    protected function assertNotNull($actual, $message = '')
    {
        return $this->phpUnit->assertNotNull($actual, $message = '');
    }


    /**
     * Asserts that a variable is null.
     *
     * @param mixed  $actual
     * @param string $message
     */
    protected function assertNull($actual, $message = '')
    {
        return $this->phpUnit->assertNull($actual, $message = '');
    }


    /**
     * Asserts that a class has a specified attribute.
     *
     * @param string $attributeName
     * @param string $className
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertClassHasAttribute($attributeName, $className, $message = '')
    {
        return $this->phpUnit->assertClassHasAttribute($attributeName, $className, $message = '');
    }


    /**
     * Asserts that a class does not have a specified attribute.
     *
     * @param string $attributeName
     * @param string $className
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertClassNotHasAttribute($attributeName, $className, $message = '')
    {
        return $this->phpUnit->assertClassNotHasAttribute($attributeName, $className, $message = '');
    }


    /**
     * Asserts that a class has a specified static attribute.
     *
     * @param string $attributeName
     * @param string $className
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertClassHasStaticAttribute($attributeName, $className, $message = '')
    {
        return $this->phpUnit->assertClassHasStaticAttribute($attributeName, $className, $message = '');
    }


    /**
     * Asserts that a class does not have a specified static attribute.
     *
     * @param string $attributeName
     * @param string $className
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertClassNotHasStaticAttribute($attributeName, $className, $message = '')
    {
        return $this->phpUnit->assertClassNotHasStaticAttribute($attributeName, $className, $message = '');
    }


    /**
     * Asserts that an object has a specified attribute.
     *
     * @param string $attributeName
     * @param object $object
     * @param string $message
     *
     * @since  Method available since Release 3.0.0
     */
    protected function assertObjectHasAttribute($attributeName, $object, $message = '')
    {
        return $this->phpUnit->assertObjectHasAttribute($attributeName, $object, $message = '');
    }


    /**
     * Asserts that an object does not have a specified attribute.
     *
     * @param string $attributeName
     * @param object $object
     * @param string $message
     *
     * @since  Method available since Release 3.0.0
     */
    protected function assertObjectNotHasAttribute($attributeName, $object, $message = '')
    {
        return $this->phpUnit->assertObjectNotHasAttribute($attributeName, $object, $message = '');
    }


    /**
     * Asserts that two variables have the same type and value.
     * Used on objects, it asserts that two variables reference
     * the same object.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    protected function assertSame($expected, $actual, $message = '')
    {
        return $this->phpUnit->assertSame($expected, $actual, $message = '');
    }


    /**
     * Asserts that a variable and an attribute of an object have the same type
     * and value.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param object $actualClassOrObject
     * @param string $message
     */
    protected function assertAttributeSame($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        return $this->phpUnit->assertAttributeSame($expected, $actualAttributeName, $actualClassOrObject,
            $message = '');
    }


    /**
     * Asserts that two variables do not have the same type and value.
     * Used on objects, it asserts that two variables do not reference
     * the same object.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    protected function assertNotSame($expected, $actual, $message = '')
    {
        return $this->phpUnit->assertNotSame($expected, $actual, $message = '');
    }


    /**
     * Asserts that a variable and an attribute of an object do not have the
     * same type and value.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param object $actualClassOrObject
     * @param string $message
     */
    protected function assertAttributeNotSame($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        return $this->phpUnit->assertAttributeNotSame($expected, $actualAttributeName, $actualClassOrObject,
            $message = '');
    }


    /**
     * Asserts that a variable is of a given type.
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     *
     * @since Method available since Release 3.5.0
     */
    protected function assertInstanceOf($expected, $actual, $message = '')
    {
        return $this->phpUnit->assertInstanceOf($expected, $actual, $message = '');
    }


    /**
     * Asserts that an attribute is of a given type.
     *
     * @param string $expected
     * @param string $attributeName
     * @param mixed  $classOrObject
     * @param string $message
     *
     * @since Method available since Release 3.5.0
     */
    protected function assertAttributeInstanceOf($expected, $attributeName, $classOrObject, $message = '')
    {
        return $this->phpUnit->assertAttributeInstanceOf($expected, $attributeName, $classOrObject, $message = '');
    }


    /**
     * Asserts that a variable is not of a given type.
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     *
     * @since Method available since Release 3.5.0
     */
    protected function assertNotInstanceOf($expected, $actual, $message = '')
    {
        return $this->phpUnit->assertNotInstanceOf($expected, $actual, $message = '');
    }


    /**
     * Asserts that an attribute is of a given type.
     *
     * @param string $expected
     * @param string $attributeName
     * @param mixed  $classOrObject
     * @param string $message
     *
     * @since Method available since Release 3.5.0
     */
    protected function assertAttributeNotInstanceOf($expected, $attributeName, $classOrObject, $message = '')
    {
        return $this->phpUnit->assertAttributeNotInstanceOf($expected, $attributeName, $classOrObject, $message = '');
    }


    /**
     * Asserts that a variable is of a given type.
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     *
     * @since Method available since Release 3.5.0
     */
    protected function assertInternalType($expected, $actual, $message = '')
    {
        return $this->phpUnit->assertInternalType($expected, $actual, $message = '');
    }


    /**
     * Asserts that an attribute is of a given type.
     *
     * @param string $expected
     * @param string $attributeName
     * @param mixed  $classOrObject
     * @param string $message
     *
     * @since Method available since Release 3.5.0
     */
    protected function assertAttributeInternalType($expected, $attributeName, $classOrObject, $message = '')
    {
        return $this->phpUnit->assertAttributeInternalType($expected, $attributeName, $classOrObject, $message = '');
    }


    /**
     * Asserts that a variable is not of a given type.
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     *
     * @since Method available since Release 3.5.0
     */
    protected function assertNotInternalType($expected, $actual, $message = '')
    {
        return $this->phpUnit->assertNotInternalType($expected, $actual, $message = '');
    }


    /**
     * Asserts that an attribute is of a given type.
     *
     * @param string $expected
     * @param string $attributeName
     * @param mixed  $classOrObject
     * @param string $message
     *
     * @since Method available since Release 3.5.0
     */
    protected function assertAttributeNotInternalType($expected, $attributeName, $classOrObject, $message = '')
    {
        return $this->phpUnit->assertAttributeNotInternalType($expected, $attributeName, $classOrObject, $message = '');
    }


    /**
     * Asserts that a string matches a given regular expression.
     *
     * @param string $pattern
     * @param string $string
     * @param string $message
     */
    protected function assertRegExp($pattern, $string, $message = '')
    {
        return $this->phpUnit->assertRegExp($pattern, $string, $message = '');
    }


    /**
     * Asserts that a string does not match a given regular expression.
     *
     * @param string $pattern
     * @param string $string
     * @param string $message
     *
     * @since  Method available since Release 2.1.0
     */
    protected function assertNotRegExp($pattern, $string, $message = '')
    {
        return $this->phpUnit->assertNotRegExp($pattern, $string, $message = '');
    }


    /**
     * Assert that the size of two arrays (or `Countable` or `Traversable` objects)
     * is the same.
     *
     * @param array|Countable|Traversable $expected
     * @param array|Countable|Traversable $actual
     * @param string                      $message
     */
    protected function assertSameSize($expected, $actual, $message = '')
    {
        return $this->phpUnit->assertSameSize($expected, $actual, $message = '');
    }


    /**
     * Assert that the size of two arrays (or `Countable` or `Traversable` objects)
     * is not the same.
     *
     * @param array|Countable|Traversable $expected
     * @param array|Countable|Traversable $actual
     * @param string                      $message
     */
    protected function assertNotSameSize($expected, $actual, $message = '')
    {
        return $this->phpUnit->assertNotSameSize($expected, $actual, $message = '');
    }


    /**
     * Asserts that a string matches a given format string.
     *
     * @param string $format
     * @param string $string
     * @param string $message
     *
     * @since  Method available since Release 3.5.0
     */
    protected function assertStringMatchesFormat($format, $string, $message = '')
    {
        return $this->phpUnit->assertStringMatchesFormat($format, $string, $message = '');
    }


    /**
     * Asserts that a string does not match a given format string.
     *
     * @param string $format
     * @param string $string
     * @param string $message
     *
     * @since  Method available since Release 3.5.0
     */
    protected function assertStringNotMatchesFormat($format, $string, $message = '')
    {
        return $this->phpUnit->assertStringNotMatchesFormat($format, $string, $message = '');
    }


    /**
     * Asserts that a string matches a given format file.
     *
     * @param string $formatFile
     * @param string $string
     * @param string $message
     *
     * @since  Method available since Release 3.5.0
     */
    protected function assertStringMatchesFormatFile($formatFile, $string, $message = '')
    {
        return $this->phpUnit->assertStringMatchesFormatFile($formatFile, $string, $message = '');
    }


    /**
     * Asserts that a string does not match a given format string.
     *
     * @param string $formatFile
     * @param string $string
     * @param string $message
     *
     * @since  Method available since Release 3.5.0
     */
    protected function assertStringNotMatchesFormatFile($formatFile, $string, $message = '')
    {
        return $this->phpUnit->assertStringNotMatchesFormatFile($formatFile, $string, $message = '');
    }


    /**
     * Asserts that a string starts with a given prefix.
     *
     * @param string $prefix
     * @param string $string
     * @param string $message
     *
     * @since  Method available since Release 3.4.0
     */
    protected function assertStringStartsWith($prefix, $string, $message = '')
    {
        return $this->phpUnit->assertStringStartsWith($prefix, $string, $message = '');
    }


    /**
     * Asserts that a string starts not with a given prefix.
     *
     * @param string $prefix
     * @param string $string
     * @param string $message
     *
     * @since  Method available since Release 3.4.0
     */
    protected function assertStringStartsNotWith($prefix, $string, $message = '')
    {
        return $this->phpUnit->assertStringStartsNotWith($prefix, $string, $message = '');
    }


    /**
     * Asserts that a string ends with a given suffix.
     *
     * @param string $suffix
     * @param string $string
     * @param string $message
     *
     * @since  Method available since Release 3.4.0
     */
    protected function assertStringEndsWith($suffix, $string, $message = '')
    {
        return $this->phpUnit->assertStringEndsWith($suffix, $string, $message = '');
    }


    /**
     * Asserts that a string ends not with a given suffix.
     *
     * @param string $suffix
     * @param string $string
     * @param string $message
     *
     * @since  Method available since Release 3.4.0
     */
    protected function assertStringEndsNotWith($suffix, $string, $message = '')
    {
        return $this->phpUnit->assertStringEndsNotWith($suffix, $string, $message = '');
    }


    /**
     * Asserts that two XML files are equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertXmlFileEqualsXmlFile($expectedFile, $actualFile, $message = '')
    {
        return $this->phpUnit->assertXmlFileEqualsXmlFile($expectedFile, $actualFile, $message = '');
    }


    /**
     * Asserts that two XML files are not equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertXmlFileNotEqualsXmlFile($expectedFile, $actualFile, $message = '')
    {
        return $this->phpUnit->assertXmlFileNotEqualsXmlFile($expectedFile, $actualFile, $message = '');
    }


    /**
     * Asserts that two XML documents are equal.
     *
     * @param string $expectedFile
     * @param string $actualXml
     * @param string $message
     *
     * @since  Method available since Release 3.3.0
     */
    protected function assertXmlStringEqualsXmlFile($expectedFile, $actualXml, $message = '')
    {
        return $this->phpUnit->assertXmlStringEqualsXmlFile($expectedFile, $actualXml, $message = '');
    }


    /**
     * Asserts that two XML documents are not equal.
     *
     * @param string $expectedFile
     * @param string $actualXml
     * @param string $message
     *
     * @since  Method available since Release 3.3.0
     */
    protected function assertXmlStringNotEqualsXmlFile($expectedFile, $actualXml, $message = '')
    {
        return $this->phpUnit->assertXmlStringNotEqualsXmlFile($expectedFile, $actualXml, $message = '');
    }


    /**
     * Asserts that two XML documents are equal.
     *
     * @param string $expectedXml
     * @param string $actualXml
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertXmlStringEqualsXmlString($expectedXml, $actualXml, $message = '')
    {
        return $this->phpUnit->assertXmlStringEqualsXmlString($expectedXml, $actualXml, $message = '');
    }


    /**
     * Asserts that two XML documents are not equal.
     *
     * @param string $expectedXml
     * @param string $actualXml
     * @param string $message
     *
     * @since  Method available since Release 3.1.0
     */
    protected function assertXmlStringNotEqualsXmlString($expectedXml, $actualXml, $message = '')
    {
        return $this->phpUnit->assertXmlStringNotEqualsXmlString($expectedXml, $actualXml, $message = '');
    }


    /**
     * Asserts that a hierarchy of DOMElements matches.
     *
     * @param DOMElement $expectedElement
     * @param DOMElement $actualElement
     * @param bool       $checkAttributes
     * @param string     $message
     *
     * @since  Method available since Release 3.3.0
     */
    protected function assertEqualXMLStructure(
        $expectedElement,
        $actualElement,
        $checkAttributes = false,
        $message = ''
    ) {
        return $this->phpUnit->assertEqualXMLStructure($expectedElement, $actualElement, $checkAttributes = false,
            $message = '');
    }


    /**
     * Assert the presence, absence, or count of elements in a document matching
     * the CSS $selector, regardless of the contents of those elements.
     *
     * The first argument, $selector, is the CSS selector used to match
     * the elements in the $actual document.
     *
     * The second argument, $count, can be either boolean or numeric.
     * When boolean, it asserts for presence of elements matching the selector
     * (true) or absence of elements (false).
     * When numeric, it asserts the count of elements.
     *
     * assertSelectCount("#binder", true, $xml);  // any?
     * assertSelectCount(".binder", 3, $xml);     // exactly 3?
     *
     * @param array          $selector
     * @param int|bool|array $count
     * @param mixed          $actual
     * @param string         $message
     * @param bool           $isHtml
     *
     * @since  Method available since Release 3.3.0
     * @deprecated
     * @codeCoverageIgnore
     */
    protected function assertSelectCount($selector, $count, $actual, $message = '', $isHtml = true)
    {
        return $this->phpUnit->assertSelectCount($selector, $count, $actual, $message = '', $isHtml = true);
    }


    /**
     * assertSelectRegExp("#binder .name", "/Mike|Derek/", true, $xml); // any?
     * assertSelectRegExp("#binder .name", "/Mike|Derek/", 3, $xml);    // 3?
     *
     * @param array          $selector
     * @param string         $pattern
     * @param int|bool|array $count
     * @param mixed          $actual
     * @param string         $message
     * @param bool           $isHtml
     *
     * @since  Method available since Release 3.3.0
     * @deprecated
     * @codeCoverageIgnore
     */
    protected function assertSelectRegExp($selector, $pattern, $count, $actual, $message = '', $isHtml = true)
    {
        return $this->phpUnit->assertSelectRegExp($selector, $pattern, $count, $actual, $message = '', $isHtml = true);
    }


    /**
     * assertSelectEquals("#binder .name", "Chuck", true,  $xml);  // any?
     * assertSelectEquals("#binder .name", "Chuck", false, $xml);  // none?
     *
     * @param array          $selector
     * @param string         $content
     * @param int|bool|array $count
     * @param mixed          $actual
     * @param string         $message
     * @param bool           $isHtml
     *
     * @since  Method available since Release 3.3.0
     * @deprecated
     * @codeCoverageIgnore
     */
    protected function assertSelectEquals($selector, $content, $count, $actual, $message = '', $isHtml = true)
    {
        return $this->phpUnit->assertSelectEquals($selector, $content, $count, $actual, $message = '', $isHtml = true);
    }


    /**
     * Evaluate an HTML or XML string and assert its structure and/or contents.
     *
     * The first argument ($matcher) is an associative array that specifies the
     * match criteria for the assertion:
     *
     *  - `id`           : the node with the given id attribute must match the
     *                     corresponding value.
     *  - `tag`          : the node type must match the corresponding value.
     *  - `attributes`   : a hash. The node's attributes must match the
     *                     corresponding values in the hash.
     *  - `content`      : The text content must match the given value.
     *  - `parent`       : a hash. The node's parent must match the
     *                     corresponding hash.
     *  - `child`        : a hash. At least one of the node's immediate children
     *                     must meet the criteria described by the hash.
     *  - `ancestor`     : a hash. At least one of the node's ancestors must
     *                     meet the criteria described by the hash.
     *  - `descendant`   : a hash. At least one of the node's descendants must
     *                     meet the criteria described by the hash.
     *  - `children`     : a hash, for counting children of a node.
     *                     Accepts the keys:
     *    - `count`        : a number which must equal the number of children
     *                       that match
     *    - `less_than`    : the number of matching children must be greater
     *                       than this number
     *    - `greater_than` : the number of matching children must be less than
     *                       this number
     *    - `only`         : another hash consisting of the keys to use to match
     *                       on the children, and only matching children will be
     *                       counted
     *
     * <code>
     * // Matcher that asserts that there is an element with an id="my_id".
     * $matcher = array('id' => 'my_id');
     *
     * // Matcher that asserts that there is a "span" tag.
     * $matcher = array('tag' => 'span');
     *
     * // Matcher that asserts that there is a "span" tag with the content
     * // "Hello World".
     * $matcher = array('tag' => 'span', 'content' => 'Hello World');
     *
     * // Matcher that asserts that there is a "span" tag with content matching
     * // the regular expression pattern.
     * $matcher = array('tag' => 'span', 'content' => 'regexp:/Try P(HP|ython)/');
     *
     * // Matcher that asserts that there is a "span" with an "list" class
     * // attribute.
     * $matcher = array(
     *   'tag'        => 'span',
     *   'attributes' => array('class' => 'list')
     * );
     *
     * // Matcher that asserts that there is a "span" inside of a "div".
     * $matcher = array(
     *   'tag'    => 'span',
     *   'parent' => array('tag' => 'div')
     * );
     *
     * // Matcher that asserts that there is a "span" somewhere inside a
     * // "table".
     * $matcher = array(
     *   'tag'      => 'span',
     *   'ancestor' => array('tag' => 'table')
     * );
     *
     * // Matcher that asserts that there is a "span" with at least one "em"
     * // child.
     * $matcher = array(
     *   'tag'   => 'span',
     *   'child' => array('tag' => 'em')
     * );
     *
     * // Matcher that asserts that there is a "span" containing a (possibly
     * // nested) "strong" tag.
     * $matcher = array(
     *   'tag'        => 'span',
     *   'descendant' => array('tag' => 'strong')
     * );
     *
     * // Matcher that asserts that there is a "span" containing 5-10 "em" tags
     * // as immediate children.
     * $matcher = array(
     *   'tag'      => 'span',
     *   'children' => array(
     *     'less_than'    => 11,
     *     'greater_than' => 4,
     *     'only'         => array('tag' => 'em')
     *   )
     * );
     *
     * // Matcher that asserts that there is a "div", with an "ul" ancestor and
     * // a "li" parent (with class="enum"), and containing a "span" descendant
     * // that contains an element with id="my_test" and the text "Hello World".
     * $matcher = array(
     *   'tag'        => 'div',
     *   'ancestor'   => array('tag' => 'ul'),
     *   'parent'     => array(
     *     'tag'        => 'li',
     *     'attributes' => array('class' => 'enum')
     *   ),
     *   'descendant' => array(
     *     'tag'   => 'span',
     *     'child' => array(
     *       'id'      => 'my_test',
     *       'content' => 'Hello World'
     *     )
     *   )
     * );
     *
     * // Use assertTag() to apply a $matcher to a piece of $html.
     * $this->assertTag($matcher, $html);
     *
     * // Use assertTag() to apply a $matcher to a piece of $xml.
     * $this->assertTag($matcher, $xml, '', false);
     * </code>
     *
     * The second argument ($actual) is a string containing either HTML or
     * XML text to be tested.
     *
     * The third argument ($message) is an optional message that will be
     * used if the assertion fails.
     *
     * The fourth argument ($html) is an optional flag specifying whether
     * to load the $actual string into a DOMDocument using the HTML or
     * XML load strategy.  It is true by default, which assumes the HTML
     * load strategy.  In many cases, this will be acceptable for XML as well.
     *
     * @param array  $matcher
     * @param string $actual
     * @param string $message
     * @param bool   $isHtml
     *
     * @since  Method available since Release 3.3.0
     * @deprecated
     * @codeCoverageIgnore
     */
    protected function assertTag($matcher, $actual, $message = '', $isHtml = true)
    {
        return $this->phpUnit->assertTag($matcher, $actual, $message = '', $isHtml = true);
    }


    /**
     * This assertion is the exact opposite of assertTag().
     *
     * Rather than asserting that $matcher results in a match, it asserts that
     * $matcher does not match.
     *
     * @param array  $matcher
     * @param string $actual
     * @param string $message
     * @param bool   $isHtml
     *
     * @since  Method available since Release 3.3.0
     * @deprecated
     * @codeCoverageIgnore
     */
    protected function assertNotTag($matcher, $actual, $message = '', $isHtml = true)
    {
        return $this->phpUnit->assertNotTag($matcher, $actual, $message = '', $isHtml = true);
    }


    /**
     * Evaluates a PHPUnit_Framework_Constraint matcher object.
     *
     * @param mixed                        $value
     * @param PHPUnit_Framework_Constraint $constraint
     * @param string                       $message
     *
     * @since  Method available since Release 3.0.0
     */
    protected function assertThat($value, $constraint, $message = '')
    {
        return $this->phpUnit->assertThat($value, $constraint, $message = '');
    }


    /**
     * Asserts that a string is a valid JSON string.
     *
     * @param string $actualJson
     * @param string $message
     *
     * @since  Method available since Release 3.7.20
     */
    protected function assertJson($actualJson, $message = '')
    {
        return $this->phpUnit->assertJson($actualJson, $message = '');
    }


    /**
     * Asserts that two given JSON encoded objects or arrays are equal.
     *
     * @param string $expectedJson
     * @param string $actualJson
     * @param string $message
     */
    protected function assertJsonStringEqualsJsonString($expectedJson, $actualJson, $message = '')
    {
        return $this->phpUnit->assertJsonStringEqualsJsonString($expectedJson, $actualJson, $message = '');
    }


    /**
     * Asserts that two given JSON encoded objects or arrays are not equal.
     *
     * @param string $expectedJson
     * @param string $actualJson
     * @param string $message
     */
    protected function assertJsonStringNotEqualsJsonString($expectedJson, $actualJson, $message = '')
    {
        return $this->phpUnit->assertJsonStringNotEqualsJsonString($expectedJson, $actualJson, $message = '');
    }


    /**
     * Asserts that the generated JSON encoded object and the content of the given file are equal.
     *
     * @param string $expectedFile
     * @param string $actualJson
     * @param string $message
     */
    protected function assertJsonStringEqualsJsonFile($expectedFile, $actualJson, $message = '')
    {
        return $this->phpUnit->assertJsonStringEqualsJsonFile($expectedFile, $actualJson, $message = '');
    }


    /**
     * Asserts that the generated JSON encoded object and the content of the given file are not equal.
     *
     * @param string $expectedFile
     * @param string $actualJson
     * @param string $message
     */
    protected function assertJsonStringNotEqualsJsonFile($expectedFile, $actualJson, $message = '')
    {
        return $this->phpUnit->assertJsonStringNotEqualsJsonFile($expectedFile, $actualJson, $message = '');
    }


    /**
     * Asserts that two JSON files are not equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     */
    protected function assertJsonFileNotEqualsJsonFile($expectedFile, $actualFile, $message = '')
    {
        return $this->phpUnit->assertJsonFileNotEqualsJsonFile($expectedFile, $actualFile, $message = '');
    }


    /**
     * Asserts that two JSON files are equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     */
    protected function assertJsonFileEqualsJsonFile($expectedFile, $actualFile, $message = '')
    {
        return $this->phpUnit->assertJsonFileEqualsJsonFile($expectedFile, $actualFile, $message = '');
    }

}
