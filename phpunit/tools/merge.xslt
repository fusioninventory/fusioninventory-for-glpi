<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:str="http://exslt.org/strings"
    exclude-result-prefixes="str">
    <xsl:output indent="yes" method="xml"/>
    <xsl:param name='logdir' select="concat($basedir,'/build/logs')"/>

    <xsl:template match="/">
        <testsuites>
            <xsl:for-each select="str:tokenize('update install unit integration',' ')">
                <xsl:variable name='file' select="concat($logdir,'/junit_',.,'.xml')"/>
                <xsl:copy-of select="document($file)/testsuites/*" />
            </xsl:for-each>
        </testsuites>
    </xsl:template>

</xsl:stylesheet>
