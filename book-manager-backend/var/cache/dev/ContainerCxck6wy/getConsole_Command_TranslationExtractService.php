<?php

namespace ContainerCxck6wy;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getConsole_Command_TranslationExtractService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'console.command.translation_extract' shared service.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Command\TranslationUpdateCommand
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/console/Command/Command.php';
        include_once \dirname(__DIR__, 4).'/vendor/symfony/framework-bundle/Command/TranslationUpdateCommand.php';

        $container->privates['console.command.translation_extract'] = $instance = new \Symfony\Bundle\FrameworkBundle\Command\TranslationUpdateCommand(($container->privates['translation.writer'] ?? $container->load('getTranslation_WriterService')), ($container->privates['translation.reader'] ?? $container->load('getTranslation_ReaderService')), ($container->privates['translation.extractor'] ?? $container->load('getTranslation_ExtractorService')), 'en', (\dirname(__DIR__, 4).'/translations'), (\dirname(__DIR__, 4).'/templates'), [(\dirname(__DIR__, 4).'/vendor/symfony/validator/Resources/translations'), (\dirname(__DIR__, 4).'/vendor/symfony/form/Resources/translations'), (\dirname(__DIR__, 4).'/vendor/symfony/security-core/Resources/translations')], [(\dirname(__DIR__, 4).'/vendor/symfony/twig-bridge/Resources/views/Email'), (\dirname(__DIR__, 4).'/vendor/symfony/twig-bridge/Resources/views/Form'), (\dirname(__DIR__, 4).'/vendor/symfony/http-kernel/Controller/ArgumentResolver/RequestPayloadValueResolver.php'), (\dirname(__DIR__, 4).'/vendor/symfony/framework-bundle/Command/TranslationDebugCommand.php'), (\dirname(__DIR__, 4).'/vendor/symfony/framework-bundle/CacheWarmer/TranslationsCacheWarmer.php'), (\dirname(__DIR__, 4).'/vendor/symfony/form/Extension/Core/Type/ChoiceType.php'), (\dirname(__DIR__, 4).'/vendor/symfony/form/Extension/Core/Type/FileType.php'), (\dirname(__DIR__, 4).'/vendor/symfony/form/Extension/Core/Type/ColorType.php'), (\dirname(__DIR__, 4).'/vendor/symfony/form/Extension/Core/Type/TransformationFailureExtension.php'), (\dirname(__DIR__, 4).'/vendor/symfony/form/Extension/Validator/Type/FormTypeValidatorExtension.php'), (\dirname(__DIR__, 4).'/vendor/symfony/form/Extension/Validator/Type/UploadValidatorExtension.php'), (\dirname(__DIR__, 4).'/vendor/symfony/form/Extension/Csrf/Type/FormTypeCsrfExtension.php'), (\dirname(__DIR__, 4).'/vendor/symfony/validator/ValidatorBuilder.php'), (\dirname(__DIR__, 4).'/vendor/symfony/serializer/Normalizer/ProblemNormalizer.php'), (\dirname(__DIR__, 4).'/vendor/symfony/serializer/SerializerAwareTrait.php'), (\dirname(__DIR__, 4).'/vendor/symfony/serializer/Normalizer/TranslatableNormalizer.php'), (\dirname(__DIR__, 4).'/vendor/symfony/twig-bridge/Extension/TranslationExtension.php'), (\dirname(__DIR__, 4).'/vendor/symfony/twig-bridge/Extension/FormExtension.php'), (\dirname(__DIR__, 4).'/vendor/symfony/translation/DataCollector/TranslationDataCollector.php')], []);

        $instance->setName('translation:extract');
        $instance->setDescription('Extract missing translations keys from code to translation files');

        return $instance;
    }
}